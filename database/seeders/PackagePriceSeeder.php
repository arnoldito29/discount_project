<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackagePrice;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class PackagePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        PackagePrice::firstOrCreate([
            'provider_id' => Provider::where('slug', 'SimoSiuntos')->first()->id,
            'package_id' => Package::where('slug', 'S')->first()->id,
            'price' => 1.5,
        ]);
        PackagePrice::firstOrCreate([
            'provider_id' => Provider::where('slug', 'SimoSiuntos')->first()->id,
            'package_id' => Package::where('slug', 'M')->first()->id,
            'price' => 4.9,
        ]);
        PackagePrice::firstOrCreate([
            'provider_id' => Provider::where('slug', 'SimoSiuntos')->first()->id,
            'package_id' => Package::where('slug', 'L')->first()->id,
            'price' => 6.9,
        ]);
        PackagePrice::firstOrCreate([
            'provider_id' => Provider::where('slug', 'JonasShipping')->first()->id,
            'package_id' => Package::where('slug', 'S')->first()->id,
            'price' => 2,
        ]);
        PackagePrice::firstOrCreate([
            'provider_id' => Provider::where('slug', 'JonasShipping')->first()->id,
            'package_id' => Package::where('slug', 'M')->first()->id,
            'price' => 3,
        ]);
        PackagePrice::firstOrCreate([
            'provider_id' => Provider::where('slug', 'JonasShipping')->first()->id,
            'package_id' => Package::where('slug', 'L')->first()->id,
            'price' => 4,
        ]);
    }
}
