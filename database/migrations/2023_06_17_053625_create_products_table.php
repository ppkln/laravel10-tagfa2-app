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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_no');
            $table->text('product_title');
            $table->text('product_description')->nullable();
            $table->string('productcover_folder')->nullable();
            $table->string('productcover_img')->nullable();
            $table->float('product_price')->nullable();
            $table->string('product_unit')->nullable();
            $table->string('user_id');
            $table->string('product_category')->nullable();
            $table->boolean('publish_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
