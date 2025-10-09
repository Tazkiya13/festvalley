<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // customer
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->decimal('total', 15, 2);
            $table->enum('status', ['waiting', 'paid', 'rejected', 'unpaid'])->default('unpaid'); // pending, approved, paid, cancelled
            $table->unsignedBigInteger('available_date_id')->nullable(); // Tanggal yang dipilih oleh user
            $table->foreign('available_date_id')->references('id')->on('available_dates')->onDelete('cascade');
            $table->timestamp('transaction_date')->nullable(); // Tanggal transaksi
            $table->string('transaction_id')->nullable(); // ID transaksi dari payment gateway
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
