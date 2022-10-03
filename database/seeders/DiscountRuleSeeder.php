<?php

namespace Database\Seeders;

use App\Models\DiscountRule;
use Illuminate\Database\Seeder;

class DiscountRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DiscountRule::firstOrCreate([
            'name' => 'lower',
            'rule' => [
                'lower' => true,
                'size' => 'S',
            ],
        ]);
        DiscountRule::firstOrCreate([
            'name' => 'first',
            'rule' => [
                'first' => true,
                'provider' => 'SimoSiuntos',
                'calendar' => 'month',
                'size' => 'L',
                'after' => 3,
            ],
        ]);
        DiscountRule::firstOrCreate([
            'name' => 'max',
            'rule' => [
                'max' => 10,
                'calendar' => 'month',
            ],
        ]);
    }
}
