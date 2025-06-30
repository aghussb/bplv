<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as ModelsRoles;

class Role extends ModelsRoles
{
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_menu');
    }
    
}
