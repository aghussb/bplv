<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // public
        Menu::create([
            "icon" => "pi pi-home",
            "label" => "Beranda",
            "route_name" => "home",
            "parent_id" => 0,
            "urutan" => 1,
            "is_parent" => 0,
            "layout" => "public",
            "is_permission" => 0,
            "config" => "Selalu Tampil",
            "function" => null,
        ]);

        // admin
        Menu::create([
            "icon" => "pi pi-fw pi-home",
            "label" => "Dashboard",
            "route_name" => "dashboard",
            "parent_id" => 0,
            "urutan" => 1,
            "is_parent" => 0,
            "layout" => "admin",
            "is_permission" => 0,
            "config" => "Selalu Tampil",
            "function" => null,
        ]);

        Menu::create([
            "icon" => "pi pi-fw pi-th-large",
            "label" => "Menu",
            "route_name" => "menu",
            "parent_id" => 0,
            "urutan" => 2,
            "is_parent" => 0,
            "layout" => "admin",
            "is_permission" => 0,
            "config" => "Selalu Tampil",
            "function" => null,
        ]);

        Menu::create([
            "icon" => "pi pi-users",
            "label" => "Role",
            "route_name" => "role",
            "parent_id" => 0,
            "urutan" => 3,
            "is_parent" => 0,
            "layout" => "admin",
            "is_permission" => 0,
            "config" => "Selalu Tampil",
            "function" => null,
        ]);

        Menu::create([
            "icon" => "pi pi-user",
            "label" => "Users",
            "route_name" => "user",
            "parent_id" => 0,
            "urutan" => 4,
            "is_parent" => 0,
            "layout" => "admin",
            "is_permission" => 0,
            "config" => "Selalu Tampil",
            "function" => null,
        ]);

        Menu::create([
            "icon" => "pi pi-upload",
            "label" => "Upload Configurations",
            "route_name" => "upload",
            "parent_id" => 0,
            "urutan" => 5,
            "is_parent" => 0,
            "layout" => "admin",
            "is_permission" => 0,
            "config" => "Selalu Tampil",
            "function" => null,
        ]);

        Menu::create([
            "icon" => "pi pi-align-justify",
            "label" => "Test Upload",
            "route_name" => "testUpload",
            "parent_id" => 0,
            "urutan" => 6,
            "is_parent" => 0,
            "layout" => "admin",
            "is_permission" => 0,
            "config" => "Selalu Tampil",
            "function" => null,
        ]);

        Menu::create([
            "icon" => "pi pi-align-justify",
            "label" => "Test Email",
            "route_name" => "testEmail",
            "parent_id" => 0,
            "urutan" => 7,
            "is_parent" => 0,
            "layout" => "admin",
            "is_permission" => 0,
            "config" => "Selalu Tampil",
            "function" => null,
        ]);

        Menu::create([
            "icon" => "pi pi-align-justify",
            "label" => "Test State Management",
            "route_name" => "testStateManagement",
            "parent_id" => 0,
            "urutan" => 8,
            "is_parent" => 0,
            "layout" => "admin",
            "is_permission" => 0,
            "config" => "Selalu Tampil",
            "function" => null,
        ]);

        Menu::create([
            "icon" => "pi pi-align-justify",
            "label" => "Test Realtime",
            "route_name" => "testRealtime",
            "parent_id" => 0,
            "urutan" => 9,
            "is_parent" => 0,
            "layout" => "admin",
            "is_permission" => 0,
            "config" => "Selalu Tampil",
            "function" => null,
        ]);

        Menu::create([
            "icon" => "pi pi-align-justify",
            "label" => "Trello View",
            "route_name" => "trelloView",
            "parent_id" => 0,
            "urutan" => 10,
            "is_parent" => 0,
            "layout" => "admin",
            "is_permission" => 0,
            "config" => "Selalu Tampil",
            "function" => null,
        ]);
            
    }
}
