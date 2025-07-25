<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 't_transactions';
    protected $primaryKey = 'idrec';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'property_id',
        'room_id',
        'order_id',
        'user_id',
        'user_name',
        'user_phone_number',
        'property_name',
        'transaction_date',
        'check_in',
        'check_out',
        'room_name',
        'user_email',
        'booking_days',
        'booking_months',
        'daily_price',
        'monthly_price',
        'room_price',
        'admin_fees',
        'grandtotal_price',
        'property_type',
        'transaction_type',
        'transaction_code',
        'transaction_status',
        'booking_type',        
        'notes',
        'attachment',
        'status',
        'paid_at'
    ];


    protected $casts = [
        'transaction_date' => 'datetime',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'paid_at' => 'datetime'        
    ];

    public function booking()
    {
        return $this->hasOne(Booking::class, 'order_id', 'order_id');
    }

    public function getAttachmentBase64Attribute()
    {
        return $this->attachment ? base64_encode($this->attachment) : null;
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'idrec');
    }
}
