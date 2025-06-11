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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Gunakan uuid() untuk foreign key ke examinations
            $table->uuid('examination_id');
            $table->foreign('examination_id')
                  ->references('id')
                  ->on('examinations')
                  ->onDelete('cascade');
            
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'qris', 'transfer']);
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};