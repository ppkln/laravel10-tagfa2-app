<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class posts extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_no',
        'post_title',
        'post_description',
        'postcover_folder',
        'postcover_img',
        'user_id',
        'post_category',
        'publish_status',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
