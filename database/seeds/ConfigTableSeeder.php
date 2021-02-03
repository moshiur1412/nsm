<?php

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('config')->insert([
            'next_subsidy_receipt' => 1,
            'next_award_receipt' => 1
        ]);
    }
}
