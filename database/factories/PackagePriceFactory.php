<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\PackagePrice;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PackagePrice>
 */
class PackagePriceFactory extends Factory
{
    protected $model = PackagePrice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_id' => Provider::inRandomOrder()->first()->id,
            'package_id' => Package::inRandomOrder()->first()->id,
            'price' => fake()->randomFloat(5000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
