<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocktakingLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'action', 'entity_type', 'entity_id', 'details'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
