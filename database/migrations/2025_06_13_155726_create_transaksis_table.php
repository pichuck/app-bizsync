<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->date('date')->comment('Tanggal transaksi');
            $table->enum('type', ['sale', 'purchase', 'income', 'expense'])->comment('Jenis transaksi: penjualan, pembelian, pendapatan, pengeluaran');
            $table->unsignedBigInteger('contact_id')->nullable()->comment('ID pelanggan atau supplier terkait');
            $table->string('description')->comment('Deskripsi transaksi');
            $table->decimal('total', 15, 2)->comment('Total nilai transaksi');
            $table->enum('payment_method', ['cash', 'credit'])->default('cash')->comment('Metode pembayaran');
            $table->date('due_date')->nullable()->comment('Tanggal jatuh tempo (untuk kredit)');
            $table->enum('status', ['paid', 'unpaid', 'partial'])->default('paid')->comment('Status pembayaran');
            $table->string('payment_proof')->nullable()->comment('Bukti pembayaran (path ke file)');
            $table->text('notes')->nullable()->comment('Catatan tambahan');
            $table->unsignedBigInteger('created_by')->nullable()->comment('User yang membuat transaksi');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('User yang terakhir edit transaksi');
            $table->timestamps();
            
            // Tambahan untuk foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
}