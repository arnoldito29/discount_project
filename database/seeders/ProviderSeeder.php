<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Provider::firstOrCreate([
            'name' => 'JonasShipping',
            'slug' => 'JonasShipping',
        ]);

        Provider::firstOrCreate([
            'name' => 'SimoSiuntos',
            'slug' => 'SimoSiuntos',
        ]);
    }
}
