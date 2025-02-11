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
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('before_price', 15, 2)->nullable();
            $table->decimal('after_price', 15, 2)->nullable();
            $table->integer('stock');
            $table->decimal('weight', 8, 0)->nullable()->comment('gram');
            $table->boolean('status')->default(0)->comment('0 = Active, 1 = Inactive');
            $table->string('cover_photo');
            $table->uuid('catalog_id');
            $table->foreign('catalog_id')->references('id')->on('catalog')->onDelete('cascade');
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
