<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_items_id',
        'coupon_code_id',
        'quantity',
        'total_price',
        'customer_name',
        'customer_phone',
        'customer_email'
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_items_id');
    }

    public function couponCode()
    {
        return $this->belongsTo(CouponCode::class, 'coupon_code_id');
    }
}
