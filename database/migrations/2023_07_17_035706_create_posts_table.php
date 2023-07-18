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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_no');
            $table->text('post_title');
            $table->text('post_description')->nullable();
            $table->string('postcover_folder')->nullable();
            $table->string('postcover_img')->nullable();
            $table->string('user_id');
            $table->string('post_category')->nullable();
            $table->boolean('publish_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
