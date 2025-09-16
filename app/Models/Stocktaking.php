<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocktaking extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime',
    ];


    protected $fillable = [
        'title',
        'description',
        'date',
        'status',
        'created_by',
        'signed_off_by',
    ];

    /**
     * The user who created the stocktaking.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The user who signed off the stocktaking.
     */
    public function signedOffBy()
    {
        return $this->belongsTo(User::class, 'signed_off_by');
    }

    /**
     * All region stocktakings related to this stocktaking.
     */
    public function regionStocktakings()
    {
        return $this->hasMany(RegionStocktaking::class);
    }

    /**
     * Scope to get only active stocktakings (draft, in_progress)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['draft', 'in_progress']);
    }

    /**
     * Scope to get archived stocktakings (closed, under_revision, signed_off)
     */
    public function scopeArchived($query)
    {
        return $query->whereIn('status', ['closed', 'under_revision', 'signed_off']);
    }

    public function regions()
    {
        return $this->hasMany(RegionStocktaking::class);
    }

}
