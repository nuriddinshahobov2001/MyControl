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
        Schema::create('credit__debits', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('author_id');
            $table->decimal('summa');
            $table->text('description')->nullable();
            $table->enum('type', ['credit', 'debit']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit__debits');
    }
};
