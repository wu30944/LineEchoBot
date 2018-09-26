<?php

use Illuminate\Database\Seeder;

class IdiomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $idioms = File::get(storage_path('sql/idioms.sql'));
        DB::unprepared($idioms);
    }
}
