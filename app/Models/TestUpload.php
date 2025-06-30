<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TestUpload extends Model
{
    use HasFactory;

    protected $table = 'test_upload';

    public $timestamps = false;

    protected $fillable = [
        "id",
        "file1",
        "file2",
        "file3",
        "type",
    ];

    public static function locationFile()
    {
        return "test_upload";
    }

    protected $appends = [
        'file1_download',
        'file2_download',
        'file3_download',
    ];

    public function getFile1DownloadAttribute()
    {
        return asset('/storage/' . $this->locationFile() . '/' . $this->attributes['file1']);
    }


    public function getFile2DownloadAttribute()
    {
        return asset('/storage/' . $this->locationFile() . '/' . $this->attributes['file2']);
    }

    public function getFile3DownloadAttribute()
    {
        return asset('/storage/' . $this->locationFile() . '/' . $this->attributes['file3']);
    }

}
