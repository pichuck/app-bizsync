<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\User;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil ID user admin
        $adminId = User::where('email', 'admin@example.com')->first()->id ?? 1;

        // Buat beberapa kontak dasar
        $contacts = [
            [
                'name' => 'PT. General Services',
                'type' => 'company',
                'email' => 'info@generalservices.com',
                'phone' => '021-55512345',
                'address' => 'Jl. Utama No. 78, Jakarta',
                'pic_name' => 'Dian Purnama',
                'pic_phone' => '0813-8765-4321',
                'tax_id' => '01.234.567.8-901.000',
                'bank_account' => 'BCA 1234567890 a.n PT General Services',
                'notes' => 'Untuk transaksi umum',
                'status' => 'active',
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
            [
                'name' => 'CV. Jasa Antar',
                'type' => 'company',
                'email' => 'jasaantar@example.com',
                'phone' => '021-7654321',
                'address' => 'Jl. Transportasi No. 45, Jakarta',
                'pic_name' => 'Siti Rahayu',
                'pic_phone' => '0878-9012-3456',
                'tax_id' => '02.345.678.9-012.000',
                'bank_account' => 'Mandiri 0987654321 a.n CV Jasa Antar',
                'notes' => 'Jasa pengiriman barang',
                'status' => 'active',
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
            [
                'name' => 'Arsitek Profesional',
                'type' => 'individual',
                'email' => 'arsitek@example.com',
                'phone' => '0815-1234-5678',
                'address' => 'Jakarta Selatan',
                'notes' => 'Konsultan desain bangunan',
                'status' => 'active',
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
            [
                'name' => 'PLN',
                'type' => 'service',
                'phone' => '123',
                'notes' => 'Pembayaran listrik bulanan',
                'status' => 'active',
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
}