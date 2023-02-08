<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'category_id',
        'image',
        'price',
        'price_discount',
        'order',
        'description'
    ];
    public function additionals()
    {
        return $this->hasManyThrough(Additional::class, ProductsAdditionals::class,'product_id', 'id', 'id', 'additional_id');
    }
    public function getPriceDiscountAttribute($value){
        return $value === null ? null : floatval($value);
    }
}
