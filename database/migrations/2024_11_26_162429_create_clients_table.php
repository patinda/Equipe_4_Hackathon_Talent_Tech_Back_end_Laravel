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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('last_name'); // Translated 'nom' to 'last_name'
            $table->string('first_name'); // Translated 'prenoms' to 'first_name'
            $table->string('cnib_number')->unique(); // Translated 'numero_cnib' to 'cnib_number'
            $table->string('phone_number')->unique();
            $table->decimal('total_amount');
            $table->date('issue_date'); // Translated 'date_delivrance' to 'issue_date'
            $table->date('expiry_date'); // Translated 'date_expiration' to 'expiry_date'
            $table->string('secondary_phone')->nullable(); // Translated 'telephone_secondaire' to 'secondary_phone'
            $table->date('birth_date'); // Translated 'date_naissance' to 'birth_date'
            $table->string('birth_place'); // Translated 'lieu_naissance' to 'birth_place'
            $table->string('issue_place'); // Translated 'lieu_delivrance' to 'issue_place'
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('orange_money_password')->nullable(); 

            // New fields for storing client photos
            $table->string('front_cnib_photo')->nullable(); // Front side of the CNIB
            $table->string('back_cnib_photo')->nullable(); // Back side of the CNIB
            $table->string('selfie_with_cnib')->nullable(); // Selfie with the CNIB

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
