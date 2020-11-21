<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(GendersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
    }
}
