<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Project::create([
            'code' => '000H',
            'name' => 'Head Office Balikpapan'
        ]);

        $this->call(RoleTableSeeder::class);
        $this->call(AdminUserSeeder::class);
    }
}
