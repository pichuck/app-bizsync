<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactTypeToTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Tambahkan kolom contact_type jika belum ada
            if (!Schema::hasColumn('transaksis', 'contact_type')) {
                $table->string('contact_type')->nullable()->after('contact_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'contact_type')) {
                $table->dropColumn('contact_type');
            }
        });
    }
}