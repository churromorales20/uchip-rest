<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AdminMenuRole extends Model
{
    protected $table = 'admin_menu_roles';
    protected $fillable = [
        'role_id',
        'menu_id',
    ];
}
