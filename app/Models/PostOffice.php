<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PostOffice extends Model
{
    use HasFactory;

    protected $table = 'post_office';
    
    protected $fillable = [
        'title',
        'excerpt',
        'body',
        'featured',
        'post_image',
    ];
}
