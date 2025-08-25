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
        Schema::disableForeignKeyConstraints();

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount');
            $table->date('due_date');
            $table->enum('status', ['paga', 'em aberto'])->default('em aberto');
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('account_type_id')->constrained('account_types');
            $table->foreignId('payment_methods_id')->nullable()->constrained('payment_methods');
            $table->date('payment_date')->nullable();
            $table->string('document_path')->nullable();
            $table->float('interest_rate')->nullable();
            $table->float('fine_amount')->nullable();
            $table->decimal('amount_paid')->nullable();
            $table->string('document_number')->nullable();
            $table->string('description')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
