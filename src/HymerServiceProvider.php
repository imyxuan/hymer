<?php

namespace IMyxuan\Hymer;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use IMyxuan\Hymer\Events\FormFieldsRegistered;
use IMyxuan\Hymer\Facades\Hymer as HymerFacade;
use IMyxuan\Hymer\FormFields\After\DescriptionHandler;
use IMyxuan\Hymer\Http\Middleware\HymerAdminMiddleware;
use IMyxuan\Hymer\Models\MenuItem;
use IMyxuan\Hymer\Models\Setting;
use IMyxuan\Hymer\Policies\BasePolicy;
use IMyxuan\Hymer\Policies\MenuItemPolicy;
use IMyxuan\Hymer\Policies\SettingPolicy;
use IMyxuan\Hymer\Providers\HymerDummyServiceProvider;
use IMyxuan\Hymer\Providers\HymerEventServiceProvider;
use IMyxuan\Hymer\Seed;
use IMyxuan\Hymer\Translator\Collection as TranslatorCollection;

class HymerServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Setting::class  => SettingPolicy::class,
        MenuItem::class => MenuItemPolicy::class,
    ];

    protected $gates = [
        'browse_admin',
        'browse_bread',
        'browse_database',
        'browse_media',
        'browse_compass',
    ];

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(HymerEventServiceProvider::class);
        $this->app->register(ImageServiceProvider::class);
        $this->app->register(HymerDummyServiceProvider::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('Hymer', HymerFacade::class);

        $this->app->singleton('hymer', function () {
            return new Hymer();
        });

        $this->app->singleton('HymerGuard', function () {
            return config('auth.defaults.guard', 'web');
        });

        $this->loadHelpers();

        $this->registerAlertComponents();
        $this->registerFormFields();

        $this->registerConfigs();

        if ($this->app->runningInConsole()) {
            $this->registerPublishableResources();
            $this->registerConsoleCommands();
        }

        if (!$this->app->runningInConsole() || config('app.env') == 'testing') {
            $this->registerAppCommands();
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router, Dispatcher $event)
    {
        if (config('hymer.user.add_default_role_on_register')) {
            $model = Auth::guard(app('HymerGuard'))->getProvider()->getModel();
            call_user_func($model.'::created', function ($user) use ($model) {
                if (is_null($user->role_id)) {
                    call_user_func($model.'::findOrFail', $user->id)
                        ->setRole(config('hymer.user.default_role'))
                        ->save();
                }
            });
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'hymer');

        $router->aliasMiddleware('admin.user', HymerAdminMiddleware::class);

        $this->loadTranslationsFrom(realpath(__DIR__.'/../publishable/lang'), 'hymer');

        if (config('hymer.database.autoload_migrations', true)) {
            if (config('app.env') == 'testing') {
                $this->loadMigrationsFrom(realpath(__DIR__.'/migrations'));
            }

            $this->loadMigrationsFrom(realpath(__DIR__.'/../migrations'));
        }

        $this->loadAuth();

        $this->registerViewComposers();

        $event->listen('hymer.alerts.collecting', function () {
            $this->addStorageSymlinkAlert();
        });

        $this->bootTranslatorCollectionMacros();

        if (method_exists('Paginator', 'useBootstrap')) {
            Paginator::useBootstrap();
        }
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Register view composers.
     */
    protected function registerViewComposers()
    {
        // Register alerts
        View::composer('hymer::*', function ($view) {
            $view->with('alerts', HymerFacade::alerts());
        });
    }

    /**
     * Add storage symlink alert.
     */
    protected function addStorageSymlinkAlert()
    {
        if (app('router')->current() !== null) {
            $currentRouteAction = app('router')->current()->getAction();
        } else {
            $currentRouteAction = null;
        }
        $routeName = is_array($currentRouteAction) ? Arr::get($currentRouteAction, 'as') : null;

        if ($routeName != 'hymer.dashboard') {
            return;
        }

        $storage_disk = (!empty(config('hymer.storage.disk'))) ? config('hymer.storage.disk') : 'public';

        if (request()->has('fix-missing-storage-symlink')) {
            if (file_exists(public_path('storage'))) {
                if (@readlink(public_path('storage')) == public_path('storage')) {
                    rename(public_path('storage'), 'storage_old');
                }
            }

            if (!file_exists(public_path('storage'))) {
                $this->fixMissingStorageSymlink();
            }
        } elseif ($storage_disk == 'public') {
            if (!file_exists(public_path('storage')) || @readlink(public_path('storage')) == public_path('storage')) {
                $alert = (new Alert('missing-storage-symlink', 'warning'))
                    ->title(__('hymer::error.symlink_missing_title'))
                    ->text(__('hymer::error.symlink_missing_text'))
                    ->button(__('hymer::error.symlink_missing_button'), '?fix-missing-storage-symlink=1');
                HymerFacade::addAlert($alert);
            }
        }
    }

    protected function fixMissingStorageSymlink()
    {
        app('files')->link(storage_path('app/public'), public_path('storage'));

        if (file_exists(public_path('storage'))) {
            $alert = (new Alert('fixed-missing-storage-symlink', 'success'))
                ->title(__('hymer::error.symlink_created_title'))
                ->text(__('hymer::error.symlink_created_text'));
        } else {
            $alert = (new Alert('failed-fixing-missing-storage-symlink', 'danger'))
                ->title(__('hymer::error.symlink_failed_title'))
                ->text(__('hymer::error.symlink_failed_text'));
        }

        HymerFacade::addAlert($alert);
    }

    /**
     * Register alert components.
     */
    protected function registerAlertComponents()
    {
        $components = ['title', 'text', 'button'];

        foreach ($components as $component) {
            $class = 'IMyxuan\\Hymer\\Alert\\Components\\'.ucfirst(Str::camel($component)).'Component';

            $this->app->bind("hymer.alert.components.{$component}", $class);
        }
    }

    protected function bootTranslatorCollectionMacros()
    {
        Collection::macro('translate', function () {
            $transtors = [];

            foreach ($this->all() as $item) {
                $transtors[] = call_user_func_array([$item, 'translate'], func_get_args());
            }

            return new TranslatorCollection($transtors);
        });
    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $publishablePath = dirname(__DIR__).'/publishable';

        $publishable = [
            'hymer_avatar' => [
                "{$publishablePath}/dummy_content/users/" => storage_path('app/public/users'),
            ],
            'seeders' => [
                "{$publishablePath}/database/seeders/" => database_path('seeders'),
            ],
            'config' => [
                "{$publishablePath}/config/hymer.php" => config_path('hymer.php'),
            ],

        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    public function registerConfigs()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/publishable/config/hymer.php',
            'hymer'
        );
    }

    public function loadAuth()
    {
        // DataType Policies

        // This try catch is necessary for the Package Auto-discovery
        // otherwise it will throw an error because no database
        // connection has been made yet.
        try {
            if (Schema::hasTable(HymerFacade::model('DataType')->getTable())) {
                $dataType = HymerFacade::model('DataType');
                $dataTypes = $dataType->select('policy_name', 'model_name')->get();

                foreach ($dataTypes as $dataType) {
                    $policyClass = BasePolicy::class;
                    if (isset($dataType->policy_name) && $dataType->policy_name !== ''
                        && class_exists($dataType->policy_name)) {
                        $policyClass = $dataType->policy_name;
                    }

                    $this->policies[$dataType->model_name] = $policyClass;
                }

                $this->registerPolicies();
            }
        } catch (\PDOException $e) {
            Log::info('No database connection yet in HymerServiceProvider loadAuth(). No worries, this is not a problem!');
        }

        // Gates
        foreach ($this->gates as $gate) {
            Gate::define($gate, function ($user) use ($gate) {
                return $user->hasPermission($gate);
            });
        }
    }

    protected function registerFormFields()
    {
        $formFields = [
            'text',
            'number',
            'markdown_editor',
            'image',
            'checkbox',
            'radio_btn',
            'text_area',
            'multiple_checkbox',
            'select_dropdown',
            'select_multiple',
            'code_editor',
            'color',
            'date',
            'timestamp',
            'time',
            'file',
            'multiple_images',
            'media_picker',
            'password',
            'rich_text_box',
            'hidden',
            'coordinates',
        ];

        foreach ($formFields as $formField) {
            $class = Str::studly("{$formField}_handler");

            HymerFacade::addFormField("IMyxuan\\Hymer\\FormFields\\{$class}");
        }

        HymerFacade::addAfterFormField(DescriptionHandler::class);

        event(new FormFieldsRegistered($formFields));
    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(Commands\InstallCommand::class);
        $this->commands(Commands\ControllersCommand::class);
        $this->commands(Commands\AdminCommand::class);
    }

    /**
     * Register the commands accessible from the App.
     */
    private function registerAppCommands()
    {
        $this->commands(Commands\MakeModelCommand::class);
    }
}
