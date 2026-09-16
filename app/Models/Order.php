<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'plan',
        'amount',
        'bank_code',
        'status',
        'customer_name',
        'customer_phone',
    ];

    /**
     * Format amount in VNĐ.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', '.').' đ';
    }
}
