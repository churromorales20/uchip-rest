<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AdditionalOption extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $appends = [
        'active',
    ];
    protected $fillable = [
        'additional_category',
        'name',
        'price',
        'max',
        'deleted_at',
        'order'
    ];
    public function getPriceAttribute($value){
        return floatval($value);
    }
    public function getActiveAttribute(){
        return $this->deleted_at === null;
    }
}
 