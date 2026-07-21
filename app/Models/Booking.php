<?php

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    protected $casts = [
        'workshop_id' => 'integer',
        'person_count' => 'integer',
    ];

    protected $fillable = [
        'workshop_id',
        'first_name',
        'last_name',
        'email',
        'person_count',
        'notes',
    ];

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }
}
