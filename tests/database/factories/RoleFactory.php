<?php

namespace IMyxuan\Hymer\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = \IMyxuan\Hymer\Models\Role::class;

    public function definition()
    {
        $role = $this->faker->word();

        return [
            'name'          => strtolower($role),
            'display_name'  => ucfirst($role),
        ];
    }
}
