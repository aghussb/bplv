<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use SoftDeletes;

    protected $hidden = ["deleted_at"];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    protected $fillable = [
        'icon',
        'label',
        'route_name',
        'parent_id',
        'urutan',
        'is_parent',
        'layout',
        'is_permission',
        'config',
        'function'
    ];

    protected $casts = [
        'is_permission' => 'string',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menu');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function uploadConfigurations()
    {
        return $this->hasMany(UploadConfiguration::class);
    }

}
