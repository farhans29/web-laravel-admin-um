<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingFeeTransaction extends Model
{
    use HasFactory;

    protected $table = 't_parking_fee_transaction';
    protected $primaryKey = 'idrec';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'property_id',
        'parking_fee_id',
        'transaction_id',
        'order_id',
        'user_id',
        'user_name',
        'user_phone',
        'parking_type',
        'vehicle_plate',
        'fee_amount',
        'transaction_date',
        'transaction_status',
        'paid_at',
        'verified_by',
        'verified_at',
        'notes',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fee_amount' => 'decimal:4',
        'transaction_date' => 'datetime',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }

    public function parkingFee()
    {
        return $this->belongsTo(ParkingFee::class, 'parking_fee_id', 'idrec');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'idrec');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function images()
    {
        return $this->hasMany(ParkingFeeTransactionImage::class, 'parking_transaction_id', 'idrec');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
