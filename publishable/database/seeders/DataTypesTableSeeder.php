<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use IMyxuan\Hymer\Models\DataType;

class DataTypesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'users');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'users',
                'display_name_singular' => __('hymer::seeders.data_types.user.singular'),
                'display_name_plural'   => __('hymer::seeders.data_types.user.plural'),
                'icon'                  => 'hymer-person',
                'model_name'            => 'IMyxuan\\Hymer\\Models\\User',
                'policy_name'           => 'IMyxuan\\Hymer\\Policies\\UserPolicy',
                'controller'            => 'IMyxuan\\Hymer\\Http\\Controllers\\HymerUserController',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }

        $dataType = $this->dataType('slug', 'menus');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'menus',
                'display_name_singular' => __('hymer::seeders.data_types.menu.singular'),
                'display_name_plural'   => __('hymer::seeders.data_types.menu.plural'),
                'icon'                  => 'hymer-list',
                'model_name'            => 'IMyxuan\\Hymer\\Models\\Menu',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }

        $dataType = $this->dataType('slug', 'roles');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'roles',
                'display_name_singular' => __('hymer::seeders.data_types.role.singular'),
                'display_name_plural'   => __('hymer::seeders.data_types.role.plural'),
                'icon'                  => 'hymer-lock',
                'model_name'            => 'IMyxuan\\Hymer\\Models\\Role',
                'controller'            => 'IMyxuan\\Hymer\\Http\\Controllers\\HymerRoleController',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }
    }

    /**
     * [dataType description].
     *
     * @param [type] $field [description]
     * @param [type] $for   [description]
     *
     * @return [type] [description]
     */
    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }
}
