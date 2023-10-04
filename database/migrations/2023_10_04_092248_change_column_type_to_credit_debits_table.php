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
        Schema::table('credit__debits', function (Blueprint $table) {
            $table->string('date')->change();
        });
        Schema::table('credit__debit__histories', function (Blueprint $table) {
            $table->string('date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_debits', function (Blueprint $table) {
            //
        });
    }
};
