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
        'deleted_at',
        'description',
    ];
    public function additionals()
    {
        return $this->belongsToMany(Additional::class, 'products_additionals', 'product_id', 'additional_id')->withPivot('order')->orderBy('products_additionals.order');
        /*return Additional::whereIn('id', function ($query) {
            $query->select('additional_id')->from('products_additionals')->where('country_id', $this->id);
        })->withPivot('order');*/
        //return $this->hasManyThrough(Additional::class, ProductsAdditionals::class,'product_id', 'id', 'id', 'additional_id');
    }
    public function getPriceDiscountAttribute($value){
        return $value === null ? null : floatval($value);
    }
}
