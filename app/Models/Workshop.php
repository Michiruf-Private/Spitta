<?php

namespace App\Models;

use Database\Factories\WorkshopFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workshop extends Model
{
    /** @use HasFactory<WorkshopFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
