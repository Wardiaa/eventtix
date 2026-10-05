<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'name', 'description', 'price',
        'quantity', 'quantity_sold', 'sales_start', 'sales_end',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sales_start' => 'datetime',
            'sales_end' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function available(): int
    {
        return max($this->quantity - $this->quantity_sold, 0);
    }

    /**
     * True if the sales window has a start date that hasn't been reached yet.
     */
    public function saleNotStarted(): bool
    {
        return $this->sales_start !== null && now()->lt($this->sales_start);
    }

    /**
     * True if the sales window has an end date that has already passed.
     */
    public function saleEnded(): bool
    {
        return $this->sales_end !== null && now()->gt($this->sales_end);
    }

    /**
     * True if there are no remaining tickets of this type.
     */
    public function isSoldOut(): bool
    {
        return $this->available() <= 0;
    }

    /**
     * Overall "can this be purchased right now" check. Kept for use in the
     * UI (disabling an option) and as a single source of truth; the
     * booking flow uses the three checks above individually so it can
     * show the precise reason to the user.
     */
    public function isOnSale(): bool
    {
        return ! $this->saleNotStarted() && ! $this->saleEnded() && ! $this->isSoldOut();
    }
}
