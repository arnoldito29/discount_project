<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Package::firstOrCreate([
            'name' => 'Small',
            'slug' => 'S',
        ]);
        Package::firstOrCreate([
            'name' => 'Medium',
            'slug' => 'M',
        ]);
        Package::firstOrCreate([
            'name' => 'Large',
            'slug' => 'L',
        ]);
    }
}
