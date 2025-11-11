<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Transaction;
use App\Models\Room;

class Booking extends Model
{
    use HasFactory;

    protected $table = 't_booking';
    protected $primaryKey = 'idrec';

    protected $appends = ['status'];

    protected $fillable = [
        'order_id',
        'room_id',
        'user_name',
        'user_email',
        'user_phone_number',
        'property_id',
        'check_in_at',
        'doc_type',
        'doc_path',
        'check_out_at',
        'created_by',
        'updated_by',
        'status',
        'reason',
        'description',
    ];

    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'idrec');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }


    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'order_id', 'order_id');
    }

    public function getStatusAttribute()
    {
        // Jika tidak ada transaksi terkait
        if (!$this->transaction) {
            return 'Unknown';
        }

        // Pastikan order_id match
        if ($this->order_id !== $this->transaction->order_id) {
            return 'Order ID Mismatch';
        }

        switch ($this->transaction->transaction_status) {
            case 'pending':
                return 'Waiting For Payment';
            case 'waiting':
                return 'Waiting For Confirmation';
            case 'paid':
                if (is_null($this->check_in_at) && is_null($this->check_out_at)) {
                    return 'Waiting For Check-In';
                } elseif (!is_null($this->check_in_at) && is_null($this->check_out_at)) {
                    return 'Checked-In';
                } elseif (!is_null($this->check_in_at) && !is_null($this->check_out_at)) {
                    return 'Checked-Out';
                }
                break;
            case 'canceled':
                return 'Canceled';
            case 'expired':
                return 'Expired';
            case 'failed':
                return 'Payment Failed';
        }

        return 'Unknown';
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Transaction::class,
            'order_id',      // Foreign key di Transaction
            'id',            // Foreign key di User
            'order_id',      // Local key di Booking
            'user_id'        // Local key di Transaction
        );
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }
}
