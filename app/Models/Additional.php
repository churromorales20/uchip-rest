<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Additional extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'single' ,
        'required',
        'max_items',
        'min_items',
    ];
    public function getRequiredAttribute($value) {
        return $value == '1' ? true : false;
    }
    public function getSingleAttribute($value) {
        return $value == '1' ? true : false;
    }
    public function items_data() {
        return $this->hasMany(AdditionalOption::class, 'additional_category', 'id');
    }
    public function products()
    {
        return $this->hasManyThrough(Product::class, ProductsAdditionals::class,'additional_id', 'id', 'id', 'product_id');
    }
}
 