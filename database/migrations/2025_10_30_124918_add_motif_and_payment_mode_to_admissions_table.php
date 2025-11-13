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
    Schema::table('admissions', function (Blueprint $table) {
        $table->string('motif')->nullable(); // Exemple : Césarienne, Consultation, etc.
        $table->string('payment_mode')->nullable(); // Exemple : Assurance AXA, IPM, Cash
    });
}

public function down(): void
{
    Schema::table('admissions', function (Blueprint $table) {
        $table->dropColumn(['motif', 'payment_mode']);
    });
}

};
