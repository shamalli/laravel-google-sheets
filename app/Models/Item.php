<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Status;

class Item extends Model
{
    protected $fillable = ['name', 'description', 'status'];
    
    protected $casts = [
        'status' => Status::class,
    ];
    
    // Local scope for allowed items
    public function scopeAllowed(Builder $query): void
    {
        $query->where('status', Status::Allowed);
    }
}