<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductsAdditionals extends Model
{
    protected $fillable = [
        'product_id',
        'additional_id',
        'order',
    ]; 
    public $timestamps = false;  
}
