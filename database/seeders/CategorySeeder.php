<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            [
                'gender' => '男性',
            ],
            [
                'gender' => '女性',
            ],
            [
                'gender' => 'その他',
            ]
        ];
        DB::table('categories')->insert($param);
    }
}
