<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    use HasFactory;

    protected $fillable = ['coupon', 'amount', 'expiration_date'];
    
    protected $rules = [
        'coupon' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'expiration_date' => 'required|date'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($coupon) {
            $coupon->expiration_date = now()->addDays($coupon->validity);
        });
    }
}
