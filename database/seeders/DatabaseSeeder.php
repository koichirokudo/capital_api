<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserGroup as UserGroupModel;
use App\Models\User as UserModel;
use App\Models\Capital as CapitalModel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FinancialTransactionsTableSeeder::class);
        UserGroupModel::factory(2)->create();
        UserModel::factory()->create([
            'user_group_id' => 1,
            'auth_type' => 0,
            'name' => 'kudou_kouichirou',
            'password' => bcrypt('password'),
            'email' => 'kudou_kouichirou@example.com',
        ]);
        UserModel::factory(3)->create();
        CapitalModel::factory(500)->create();
    }
}
