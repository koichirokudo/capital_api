<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('capitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->references('id')->on('users');
            $table->foreignId('user_group_id')->constrained()->onDelete('cascade')->references('id')->on('user_groups');
            $table->foreignId('settlement_id')->nullable()->constrained()->onDelete('cascade')->references('id')->on('settlements');
            $table->integer('capital_type');
            $table->date('date');
            $table->foreignId('financial_transaction_id')->constrained()->onDelete('cascade')->references('id')->on('financial_transactions');
            $table->integer('money')->default(0);
            $table->boolean('share')->default(false);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capitals');
    }
};
