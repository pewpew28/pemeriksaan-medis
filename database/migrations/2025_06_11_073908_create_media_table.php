<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // Support both UUID and integer model IDs
            $table->string('model_type');
            $table->string('model_id', 36); // 36 characters untuk UUID
            
            $table->uuid('uuid')->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable()->index();
            
            $table->nullableTimestamps();
            
            // Indexes
            $table->index(['model_type', 'model_id']);
            $table->index('collection_name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
    }
};