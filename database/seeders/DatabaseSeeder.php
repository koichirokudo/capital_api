<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Groups::create([
            'group_name' => 'test group',
            'invite_code' => 'test',
            'invite_limit' => '2024-05-10',
            'start_day' => 1,
        ]);

        \App\Models\User::factory()->create([
            'group_id' => 1,
            'auth_type' => 0,
            'name' => 'test',
            'password' => bcrypt('password'),
            'email' => 'test@example.com',
        ]);

        \App\Models\Capital::create([
            'user_id' => 1,
            'group_id' => 1,
            'share' => false,
            'date' => '2023-05-10',
            'expenses_item' => '食費',
            'capital_type' => '支出',
            'note' => 'test',
            'money' => 1000,
            'settlement' => false,
            'settlement_at' => null,
        ]);
        \App\Models\Capital::create([
            'user_id' => 1,
            'group_id' => 1,
            'share' => false,
            'date' => '2023-06-10',
            'expenses_item' => '日用品',
            'capital_type' => '支出',
            'note' => 'test',
            'money' => 1000,
            'settlement' => false,
            'settlement_at' => null,
        ]);
        \App\Models\Capital::create([
            'user_id' => 1,
            'group_id' => 1,
            'share' => false,
            'date' => '2023-05-10',
            'expenses_item' => '給料',
            'capital_type' => '収入',
            'note' => 'test',
            'money' => 1000,
            'settlement' => false,
            'settlement_at' => null,
        ]);
        \App\Models\Capital::create([
            'user_id' => 1,
            'group_id' => 1,
            'share' => false,
            'date' => '2023-06-10',
            'expenses_item' => '給料',
            'capital_type' => '収入',
            'note' => 'test',
            'money' => 1000,
            'settlement' => false,
            'settlement_at' => null,
        ]);
    }
}
