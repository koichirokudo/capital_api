<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpensesItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ExpensesItems::insert([
            [
                'label' => '食費',
                'type' => 0,
                'value' => 'food',
            ],
            [
                'label' => '日用品',
                'type' => 0,
                'value' => 'daily_goods',
            ],
            [
                'label' => '交通費',
                'type' => 0,
                'value' => 'transportation',
            ],
            [
                'label' => '交際費',
                'type' => 0,
                'value' => 'inter_communication',
            ],
            [
                'label' => '趣味・娯楽費',
                'type' => 0,
                'value' => 'hobby',
            ],
            [
                'label' => '衣服・美容費',
                'type' => 0,
                'value' => 'clothes',
            ],
            [
                'label' => '健康・医療費',
                'type' => 0,
                'value' => 'health',
            ],
            [
                'label' => '通信費',
                'type' => 0,
                'value' => 'communication',
            ],
            [
                'label' => '教養・教育費',
                'type' => 0,
                'value' => 'education'
            ],
            [
                'label' => '住宅費',
                'type' => 0,
                'value' => 'housing'
            ],
            [
                'label' => '水道・光熱費',
                'type' => 0,
                'value' => 'water_heating'
            ],
            [
                'label' => '保険料',
                'type' => 0,
                'value' => 'insurance'
            ],
            [
                'label' => '税金',
                'type' => 0,
                'value' => 'tax'
            ],
            [
                'label' => 'その他',
                'type' => 0,
                'value' => 'income_other'
            ],
            [
                'label' => '給与',
                'type' => 1,
                'value' => 'salary',
            ],
            [
                'label' => '一時所得',
                'type' => 1,
                'value' => 'temporary_income',
            ],
            [
                'label' => '事業・副業',
                'type' => 1,
                'value' => 'business',
            ],
            [
                'label' => '年金・配当金',
                'type' => 1,
                'value' => 'annuity',
            ],
            [
                'label' => '不動産所得',
                'type' => 1,
                'value' => 'real_estate',
            ],
            [
                'label' => 'その他',
                'type' => 1,
                'value' => 'expenses_other'
            ],
        ]);
    }
}
