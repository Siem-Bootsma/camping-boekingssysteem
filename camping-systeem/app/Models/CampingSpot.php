<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\CampingSpotFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'capacity', 'price_per_night', 'accommodation_type', 'image_path', 'is_active'])]
class CampingSpot extends Model
{
    /** @use HasFactory<CampingSpotFactory> */
    use HasFactory;

    public const string TYPE_TENT_PITCH = 'tent_pitch';

    public const string TYPE_CHALET = 'chalet';

    public const string TYPE_STATIC_CARAVAN = 'static_caravan';

    public const string TYPE_CAMPING_PITCH = 'camping_pitch';

    public const array TYPES = [
        self::TYPE_TENT_PITCH,
        self::TYPE_CHALET,
        self::TYPE_STATIC_CARAVAN,
        self::TYPE_CAMPING_PITCH,
    ];

    protected $attributes = [
        'capacity' => 4,
        'price_per_night' => 35,
        'accommodation_type' => self::TYPE_CAMPING_PITCH,
        'image_path' => 'images/kampeerplek1.png',
        'is_active' => true,
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailableBetween(Builder $query, CarbonInterface|string $startDate, CarbonInterface|string $endDate): Builder
    {
        return $query->whereDoesntHave('bookings', function (Builder $query) use ($startDate, $endDate): void {
            $query
                ->where('status', Booking::STATUS_CONFIRMED)
                ->overlapping($startDate, $endDate);
        });
    }

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'price_per_night' => 'decimal:2',
            'accommodation_type' => 'string',
            'image_path' => 'string',
            'is_active' => 'boolean',
        ];
    }
}
