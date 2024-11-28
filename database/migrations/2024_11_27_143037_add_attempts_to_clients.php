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
    Schema::table('clients', function (Blueprint $table) {
        // Tentatives pour vérifier le numéro de téléphone
        $table->integer('phone_attempts')->default(0);
        $table->boolean('is_phone_locked')->default(false);

        // Tentatives pour vérifier le CNIB
        $table->integer('cnib_attempts')->default(0);
        $table->boolean('is_cnib_locked')->default(false);

        // Tentatives pour vérifier le montant de la transaction
        $table->integer('amount_attempts')->default(0);
        $table->boolean('is_amount_locked')->default(false);

        // Tentatives pour vérifier le type de transaction
        $table->integer('type_attempts')->default(0);
        $table->boolean('is_type_locked')->default(false);
    });
}

public function down()
{
    Schema::table('clients', function (Blueprint $table) {
        $table->dropColumn([
            'phone_attempts', 'is_phone_locked',
            'cnib_attempts', 'is_cnib_locked',
            'amount_attempts', 'is_amount_locked',
            'type_attempts', 'is_type_locked',
        ]);
    });
}


};
