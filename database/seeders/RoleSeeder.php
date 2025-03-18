<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'user']);
        Role::create(['name' => 'editorial', 'guard_name' => 'editorial']);
        Role::create(['name' => 'reviewer', 'guard_name' => 'editorial']);
        Role::create(['name' => 'admin', 'guard_name' => 'editorial']);
    }
}
