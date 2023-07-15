<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sponsors extends Model
{
    use HasFactory;

    protected $fillable = [
        'sponsor_id',
        'sponsor_name',
        'sponsor_link',
        'sponsor_img',
        'sponsor_level',
        'user_id',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
