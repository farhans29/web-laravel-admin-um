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

    // Note: 'status' is defined as both a database column (boolean: 0=inactive, 1=active)
    // and a computed accessor that returns transaction-based status text
    // When accessing $booking->status, you get the computed text (via getStatusAttribute)
    // When querying, use ->where('status', 1) to filter by the database column

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
        'is_printed',
    ];

    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        // Note: status is NOT cast here to allow the accessor to override it
    ];

    // In your Booking model
    protected $dates = [
        'check_in_at',
        'check_out_at',
        'created_at',
        'updated_at',
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
            case 'cancelled':
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

    public function refund()
    {
        return $this->hasOne(Refund::class, 'id_booking', 'order_id');
    }

    public function getInvoiceData()
    {
        return [
            'transaction_code' => $this->transaction->transaction_code ?? 'N/A',
            'transaction_date' => $this->transaction->transaction_date ?? now(),
            'user_name' => $this->transaction->user_name ?? $this->user_name ?? 'N/A',
            'user_phone' => $this->transaction->user_phone_number ?? $this->user_phone_number ?? 'N/A',
            'user_email' => $this->transaction->user_email ?? $this->user_email ?? 'N/A',
            'property_type' => $this->transaction->property_type ?? $this->property->type ?? 'N/A',
            'check_in' => $this->transaction->check_in ?? $this->check_in_at,
            'check_out' => $this->transaction->check_out ?? $this->check_out_at,
            'room_name' => $this->transaction->room_name ?? $this->room->name ?? 'N/A',
            'booking_days' => $this->transaction->booking_days ?? 0,
            'booking_months' => $this->transaction->booking_months ?? 0,
            'booking_type' => $this->transaction->booking_type ?? 'daily',
            'room_price' => $this->transaction->room_price ?? 0,
            'admin_fees' => $this->transaction->admin_fees ?? 0,
            'grandtotal' => $this->transaction->grandtotal_price ?? 0,
            'status' => $this->transaction->transaction_status ?? 'pending'
        ];
    }
}
