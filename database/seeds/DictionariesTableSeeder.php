<?php

use Illuminate\Database\Seeder;

class DictionariesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $idioms = File::get(storage_path('sql/dictionaries.sql'));
        DB::unprepared($idioms);
    }
}
