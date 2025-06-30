<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadConfiguration extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "keterangan",
        "menu_id",
        "ekstensi",
        "ukuran",
    ];
    
    public function menus(){
    	return $this->belongsTo(Menu::class,'menu_id', 'id');
    }

}
