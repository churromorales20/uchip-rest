<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrderIdentifier extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'user_order_identifier';
    protected $fillable = ['order_identifier','order_id'];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
