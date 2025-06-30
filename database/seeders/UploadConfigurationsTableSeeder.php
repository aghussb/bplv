<?php

namespace Database\Seeders;

use App\Models\UploadConfiguration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UploadConfigurationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UploadConfiguration::create([
            "menu_id" => null,
            "keterangan" => "Test Upload Single Basic",
            "ekstensi" => ".jpg",
            "ukuran" => 1024,
        ]);

        UploadConfiguration::create([
            "menu_id" => null,
            "keterangan" => "Test Upload Single Input",
            "ekstensi" => ".jpeg",
            "ukuran" => 1024,
        ]);

        UploadConfiguration::create([
            "menu_id" => null,
            "keterangan" => "Test Upload Single In Multiple Data",
            "ekstensi" => ".rar",
            "ukuran" => 1024,
        ]);

        UploadConfiguration::create([
            "menu_id" => null,
            "keterangan" => "Test Upload Single Object 1",
            "ekstensi" => ".zip",
            "ukuran" => 1024,
        ]);

        UploadConfiguration::create([
            "menu_id" => null,
            "keterangan" => "Test Upload Single Object 2",
            "ekstensi" => ".pdf",
            "ukuran" => 1024,
        ]);

        UploadConfiguration::create([
            "menu_id" => null,
            "keterangan" => "Test Upload Single Object 3",
            "ekstensi" => ".docx",
            "ukuran" => 1024,
        ]);
    }
}
