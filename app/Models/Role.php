<?php
namespace App\Models;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function menues(){
        return $this->hasManyThrough(AdminMenu::class, AdminMenuRole::class, 'role_id', 'id', 'id', 'menu_id');
    }
}