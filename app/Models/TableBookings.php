<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableBookings extends Model
{
    protected $fillable = ['name', 'phone', 'persons', 'date', 'time'];
}
