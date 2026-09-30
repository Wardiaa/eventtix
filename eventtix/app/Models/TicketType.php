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

    public function isOnSale(): bool
    {
        $now = now();
        if ($this->sales_start && $now->lt($this->sales_start)) {
            return false;
        }
        if ($this->sales_end && $now->gt($this->sales_end)) {
            return false;
        }

        return $this->available() > 0;
    }
}
