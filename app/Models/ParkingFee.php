<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ParkingFee extends Model
{
    use HasFactory;

    protected $table = 'm_parking_fee';
    protected $primaryKey = 'idrec';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'property_id',
        'parking_type',
        'fee',
        'capacity',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fee' => 'decimal:4',
        'capacity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }

    public function transactions()
    {
        return $this->hasMany(ParkingFeeTransaction::class, 'parking_fee_id', 'idrec');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->whereHas('property', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        });
    }

    public function getAvailableCapacityAttribute(): int
    {
        $occupied = $this->transactions()
            ->whereIn('transaction_status', ['paid', 'waiting', 'pending'])
            ->where('status', 1)
            ->count();

        return max(0, $this->capacity - $occupied);
    }
}
