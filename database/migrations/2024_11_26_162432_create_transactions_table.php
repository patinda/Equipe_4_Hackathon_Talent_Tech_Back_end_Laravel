<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // Foreign key linking to clients
            $table->date('transaction_date'); // Translated 'date' to 'transaction_date'
            $table->decimal('transaction_amount'); // Translated 'montant' to 'amount'
            $table->foreignId('transaction_type_id')->constrained('type_transactions'); // Foreign key linking to transaction_types
            $table->timestamps();
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
