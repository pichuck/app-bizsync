<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id');
            $table->string('description')->comment('Deskripsi item');
            $table->integer('quantity')->default(1)->comment('Jumlah item');
            $table->decimal('price', 15, 2)->comment('Harga per item');
            $table->decimal('subtotal', 15, 2)->comment('Subtotal (quantity * price)');
            $table->unsignedBigInteger('product_id')->nullable()->comment('ID produk terkait (opsional)');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('transaksi_id')->references('id')->on('transaksis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_items');
    }
}