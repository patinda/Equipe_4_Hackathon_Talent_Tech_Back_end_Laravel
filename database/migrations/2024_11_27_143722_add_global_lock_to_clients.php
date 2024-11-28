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
        $table->boolean('is_global_locked')->default(false); // Verrouillage global
        $table->timestamp('locked_at')->nullable(); // Date de verrouillage
    });
}

public function down()
{
    Schema::table('clients', function (Blueprint $table) {
        $table->dropColumn(['is_global_locked', 'locked_at']);
    });
}

};
