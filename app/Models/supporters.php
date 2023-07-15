<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supporters extends Model
{
    use HasFactory;

    protected $fillable= [
        'supporter_id',
        'supporter_name',
        'supporter_link',
        'supporter_img',
    ];
}
