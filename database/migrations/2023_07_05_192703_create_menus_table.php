<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('label');
            $table->string('route_name')->nullable();
            $table->integer('parent_id');
            $table->integer('urutan');
            $table->boolean('is_parent');
            $table->enum('layout', ['admin', 'public']);
            $table->boolean('is_permission');
            $table->enum('config', ['Sebelum Login', 'Selalu Tampil', 'Setelah Login']);
            $table->string('function')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
