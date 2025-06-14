<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProperRelationsToTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Relasi ke customers dan suppliers berdasarkan tipe transaksi
            // contact_id sudah ada, hanya perlu modifikasi kolom contact_type
            $table->string('contact_type')->nullable()->after('contact_id')
                ->comment('Model terkait: Customer, Supplier, atau Contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('contact_type');
        });
    }
}