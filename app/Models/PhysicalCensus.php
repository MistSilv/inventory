<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalCensus extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_stocktaking_id',
        'location',
    ];

    // Relacja do region stocktaking
    public function regionStocktaking()
    {
        return $this->belongsTo(RegionStocktaking::class);
    }

    // Relacja do produktów w tym spisie fizycznym
    public function products()
    {
        return $this->belongsToMany(Product::class, 'physical_census_products')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}
