<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\DiscountRuleFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DiscountRule
 *
 * @property int $id
 * @property string $name
 * @property array $rule
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class DiscountRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rule',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'closed_at' => 'datetime',
        'rule' => 'json',
    ];

    /**
     * rules data
     *
     * @return Attribute
     */
    protected function rule(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return DiscountRuleFactory::new();
    }
}
