<?php

use Illuminate\Database\Seeder;

class GendersTableSeeder extends Seeder
{

    public function run()
    {
        factory(App\Models\Gender::class, 100)->create();
    }
}
