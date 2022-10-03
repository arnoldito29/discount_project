<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\PackagePriceFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PackagePrice
 *
 * @property int $id
 * @property int $provider_id
 * @property int $package_id
 * @property double $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PackagePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'package_id',
        'price',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return PackagePriceFactory::new();
    }
}
