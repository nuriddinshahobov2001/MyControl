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
            $table->integer('related_credit_debit_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit__debits', function (Blueprint $table) {
            $table->dropColumn('related_credit_debit_id');
        });
    }
};
