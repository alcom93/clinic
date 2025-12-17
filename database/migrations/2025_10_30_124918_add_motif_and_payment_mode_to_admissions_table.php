<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('admissions', function (Blueprint $table) {
            if (!Schema::hasColumn('admissions', 'motif')) {
                $table->string('motif')->nullable();
            }

            if (!Schema::hasColumn('admissions', 'payment_mode')) {
                $table->string('payment_mode')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn(['motif','payment_mode']);
        });
    }
};
