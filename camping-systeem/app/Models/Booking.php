<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'camping_spot_id',
    'guest_name',
    'guest_email',
    'guest_phone',
    'start_date',
    'end_date',
    'party_size',
    'status',
    'notes',
])]
class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    public const string STATUS_CONFIRMED = 'confirmed';

    protected $attributes = [
        'status' => self::STATUS_CONFIRMED,
    ];

    public function campingSpot(): BelongsTo
    {
        return $this->belongsTo(CampingSpot::class);
    }


    public function scopeOverlapping(Builder $query, CarbonInterface|string $startDate, CarbonInterface|string $endDate): Builder
    {
        return $query
            ->where('start_date', '<', $endDate)
            ->where('end_date', '>', $startDate);
    }


    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'party_size' => 'integer',
        ];
    }
}
