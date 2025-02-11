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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->dateTime('transaction_date')->index();
            $table->string('customer_name', 100);
            $table->uuid('address_id')->nullable();
            $table->text('note')->nullable();
            $table->decimal('shipping_cost', 15, 2)->nullable();
            $table->string('status')->default('pending')->comment('pending, process, completed, failed', 'refund');
            $table->enum('type_transaction', ['online', 'offline']);
            $table->string('type_payment', 100);
            $table->string('transfer_proof')->nullable();
            $table->string('courier', 100)->nullable();
            $table->string('resi', 100)->nullable();
            $table->decimal('discount', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->timestamps();

            $table->foreign('address_id')->references('id')->on('address')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
