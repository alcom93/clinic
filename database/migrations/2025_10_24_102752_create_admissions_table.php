<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
 Schema::create('admissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('patient_id')->constrained()->onDelete('cascade');
    $table->foreignId('room_id')->constrained()->onDelete('cascade');
    $table->timestamp('admitted_at')->nullable();
    $table->timestamp('discharged_at')->nullable();
    $table->string('motif')->nullable();
    $table->string('payment_mode')->nullable();
    $table->timestamps();
});

    }

    public function down() {
        Schema::dropIfExists('admissions');
    }
};

