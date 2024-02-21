<?php

namespace IMyxuan\Hymer;

use Arrilot\Widgets\Facade as Widget;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use IMyxuan\Hymer\Actions\DeleteAction;
use IMyxuan\Hymer\Actions\EditAction;
use IMyxuan\Hymer\Actions\RestoreAction;
use IMyxuan\Hymer\Actions\ViewAction;
use IMyxuan\Hymer\Events\AlertsCollection;
use IMyxuan\Hymer\FormFields\After\HandlerInterface as AfterHandlerInterface;
use IMyxuan\Hymer\FormFields\HandlerInterface;
use IMyxuan\Hymer\Models\Category;
use IMyxuan\Hymer\Models\DataRow;
use IMyxuan\Hymer\Models\DataType;
use IMyxuan\Hymer\Models\Menu;
use IMyxuan\Hymer\Models\MenuItem;
use IMyxuan\Hymer\Models\Page;
use IMyxuan\Hymer\Models\Permission;
use IMyxuan\Hymer\Models\Post;
use IMyxuan\Hymer\Models\Role;
use IMyxuan\Hymer\Models\Setting;
use IMyxuan\Hymer\Models\Translation;
use IMyxuan\Hymer\Models\User;
use IMyxuan\Hymer\Traits\Translatable;

class Hymer
{
    protected $version;
    protected $filesystem;

    protected $alerts = [];
    protected $alertsCollected = false;

    protected $formFields = [];
    protected $afterFormFields = [];

    protected $viewLoadingEvents = [];

    protected $actions = [
        DeleteAction::class,
        RestoreAction::class,
        EditAction::class,
        ViewAction::class,
    ];

    protected $models = [
        'Category'    => Category::class,
        'DataRow'     => DataRow::class,
        'DataType'    => DataType::class,
        'Menu'        => Menu::class,
        'MenuItem'    => MenuItem::class,
        'Page'        => Page::class,
        'Permission'  => Permission::class,
        'Post'        => Post::class,
        'Role'        => Role::class,
        'Setting'     => Setting::class,
        'User'        => User::class,
        'Translation' => Translation::class,
    ];

    public $setting_cache = null;

    public function __construct()
    {
        $this->filesystem = app(Filesystem::class);

        $this->findVersion();
    }

    public function model($name)
    {
        return app($this->models[Str::studly($name)]);
    }

    public function modelClass($name)
    {
        return $this->models[$name];
    }

    public function useModel($name, $object)
    {
        if (is_string($object)) {
            $object = app($object);
        }

        $class = get_class($object);

        if (isset($this->models[Str::studly($name)]) && !$object instanceof $this->models[Str::studly($name)]) {
            throw new \Exception("[{$class}] must be instance of [{$this->models[Str::studly($name)]}].");
        }

        $this->models[Str::studly($name)] = $class;

        return $this;
    }

    public function view($name, array $parameters = [])
    {
        foreach (Arr::get($this->viewLoadingEvents, $name, []) as $event) {
            $event($name, $parameters);
        }

        return view($name, $parameters);
    }

    public function onLoadingView($name, \Closure $closure)
    {
        if (!isset($this->viewLoadingEvents[$name])) {
            $this->viewLoadingEvents[$name] = [];
        }

        $this->viewLoadingEvents[$name][] = $closure;
    }

    public function formField($row, $dataType, $dataTypeContent)
    {
        if (!isset($this->formFields[$row->type])) {
            throw new \Exception(__('Missing field type: ' . $row->type), 500);
        }

        $formField = $this->formFields[$row->type];

        return $formField->handle($row, $dataType, $dataTypeContent);
    }

    public function afterFormFields($row, $dataType, $dataTypeContent)
    {
        return collect($this->afterFormFields)->filter(function ($after) use ($row, $dataType, $dataTypeContent) {
            return $after->visible($row, $dataType, $dataTypeContent, $row->details);
        });
    }

    public function addFormField($handler)
    {
        if (!$handler instanceof HandlerInterface) {
            $handler = app($handler);
        }

        $this->formFields[$handler->getCodename()] = $handler;

        return $this;
    }

    public function addAfterFormField($handler)
    {
        if (!$handler instanceof AfterHandlerInterface) {
            $handler = app($handler);
        }

        $this->afterFormFields[$handler->getCodename()] = $handler;

        return $this;
    }

    public function formFields()
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver", 'mysql');

        return collect($this->formFields)->filter(function ($after) use ($driver) {
            return $after->supports($driver);
        });
    }

    public function addAction($action)
    {
        array_push($this->actions, $action);
    }

    public function replaceAction($actionToReplace, $action)
    {
        $key = array_search($actionToReplace, $this->actions);
        $this->actions[$key] = $action;
    }

    public function actions()
    {
        return $this->actions;
    }

    /**
     * Get a collection of dashboard widgets.
     * Each of our widget groups contain a max of three widgets.
     * After that, we will switch to a new widget group.
     *
     * @return array - Array consisting of \Arrilot\Widget\WidgetGroup objects
     */
    public function dimmers()
    {
        $widgetClasses = config('hymer.dashboard.widgets');
        $dimmerGroups = [];
        $dimmerCount = 0;
        $dimmers = Widget::group("hymer::dimmers-{$dimmerCount}");

        foreach ($widgetClasses as $widgetClass) {
            $widget = app($widgetClass);

            if ($widget->shouldBeDisplayed()) {

                // Every third dimmer, we consider out WidgetGroup filled.
                // We switch that out with another WidgetGroup.
                if ($dimmerCount % 3 === 0 && $dimmerCount !== 0) {
                    $dimmerGroups[] = $dimmers;
                    $dimmerGroupTag = ceil($dimmerCount / 3);
                    $dimmers = Widget::group("hymer::dimmers-{$dimmerGroupTag}");
                }

                $dimmers->addWidget($widgetClass);
                $dimmerCount++;
            }
        }

        $dimmerGroups[] = $dimmers;

        return $dimmerGroups;
    }

    public function setting($key, $default = null)
    {
        $globalCache = config('hymer.settings.cache', false);

        if ($globalCache && Cache::tags('settings')->has($key)) {
            return Cache::tags('settings')->get($key);
        }

        if ($this->setting_cache === null) {
            if ($globalCache) {
                // A key is requested that is not in the cache
                // this is a good opportunity to update all keys
                // albeit not strictly necessary
                Cache::tags('settings')->flush();
            }

            foreach (self::model('Setting')->orderBy('order')->get() as $setting) {
                $keys = explode('.', $setting->key);
                @$this->setting_cache[$keys[0]][$keys[1]] = $setting->value;

                if ($globalCache) {
                    Cache::tags('settings')->forever($setting->key, $setting->value);
                }
            }
        }

        $parts = explode('.', $key);

        if (count($parts) == 2) {
            return @$this->setting_cache[$parts[0]][$parts[1]] ?: $default;
        } else {
            return @$this->setting_cache[$parts[0]] ?: $default;
        }
    }

    public function image($file, $default = '')
    {
        if (!empty($file)) {
            return str_replace('\\', '/', Storage::disk(config('hymer.storage.disk'))->url($file));
        }

        return $default;
    }

    public function routes()
    {
        require __DIR__.'/../routes/hymer.php';
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function addAlert(Alert $alert)
    {
        $this->alerts[] = $alert;
    }

    public function alerts()
    {
        if (!$this->alertsCollected) {
            event(new AlertsCollection($this->alerts));

            $this->alertsCollected = true;
        }

        return $this->alerts;
    }

    protected function findVersion()
    {
        if (!is_null($this->version)) {
            return;
        }

        if ($this->filesystem->exists(base_path('composer.lock'))) {
            // Get the composer.lock file
            $file = json_decode(
                $this->filesystem->get(base_path('composer.lock'))
            );

            // Loop through all the packages and get the version of hymer
            foreach ($file->packages as $package) {
                if ($package->name == 'imyxuan/hymer') {
                    $this->version = $package->version;
                    break;
                }
            }
        }
    }

    /**
     * @param string|Model|Collection $model
     *
     * @return bool
     */
    public function translatable($model)
    {
        if (!config('hymer.multilingual.enabled')) {
            return false;
        }

        if (is_string($model)) {
            $model = app($model);
        }

        if ($model instanceof Collection) {
            $model = $model->first();
        }

        if (!is_subclass_of($model, Model::class)) {
            return false;
        }

        $traits = class_uses_recursive(get_class($model));

        return in_array(Translatable::class, $traits);
    }

    public function getLocales()
    {
        $appLocales = [];
        if ($this->filesystem->exists(resource_path('lang/vendor/hymer'))) {
            $appLocales = array_diff(scandir(resource_path('lang/vendor/hymer')), ['..', '.']);
        }

        $vendorLocales = array_diff(scandir(realpath(__DIR__.'/../publishable/lang')), ['..', '.']);
        $allLocales = array_merge($vendorLocales, $appLocales);

        asort($allLocales);

        return $allLocales;
    }
}
