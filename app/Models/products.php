<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class products extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_no',
        'product_title',
        'product_description',
        'productcover_folder',
        'productcover_img',
        'product_price',
        'product_unit',
        'user_id',
        'product_category',
        'publish_status',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
