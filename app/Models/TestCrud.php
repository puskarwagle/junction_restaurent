<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestCrud extends Model
{
    protected $fillable = ['name', 'phone', 'persons', 'date', 'time', 'price', 'image_path', 'description'];

}
