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
        if (!Schema::hasTable('ad_images')) {
            Schema::create('ad_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ad_id')->constrained('ads')->onDelete('cascade');
                $table->string('path');
                $table->timestamps();
            });

            return;
        }

        if (!Schema::hasColumn('ad_images', 'ad_id')) {
            Schema::table('ad_images', function (Blueprint $table) {
                $table->foreignId('ad_id')
                    ->after('id')
                    ->constrained('ads')
                    ->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('ad_images', 'path')) {
            Schema::table('ad_images', function (Blueprint $table) {
                $table->string('path')->after('ad_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_images');
    }
};
