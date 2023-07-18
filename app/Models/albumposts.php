<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class albumposts extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_no',
        'post_no',
        'img_name',
        'user_id',
    ];
}
