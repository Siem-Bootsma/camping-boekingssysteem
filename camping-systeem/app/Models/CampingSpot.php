<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\CampingSpotFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'capacity', 'is_active'])]
class CampingSpot extends Model
{
    /** @use HasFactory<CampingSpotFactory> */
    use HasFactory;

    protected $attributes = [
        'capacity' => 4,
        'is_active' => true,
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * @param  Builder<CampingSpot>  $query
     * @return Builder<CampingSpot>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @param  Builder<CampingSpot>  $query
     * @return Builder<CampingSpot>
     */
    public function scopeAvailableBetween(Builder $query, CarbonInterface|string $startDate, CarbonInterface|string $endDate): Builder
    {
        return $query->whereDoesntHave('bookings', function (Builder $query) use ($startDate, $endDate): void {
            $query
                ->where('status', Booking::STATUS_CONFIRMED)
                ->overlapping($startDate, $endDate);
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
