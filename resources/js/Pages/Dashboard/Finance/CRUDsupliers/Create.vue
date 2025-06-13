<template>
    <DashboardFinanceLayouts>
        <div class="container mx-auto px-4 py-8 max-w-3xl">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Tambah Supplier
                </h2>

                <form @submit.prevent="submitForm" class="space-y-5">
                    <!-- Nama Supplier -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nama Supplier</label
                        >
                        <input
                            type="text"
                            v-model="form.name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                            placeholder="Nama perusahaan atau perorangan"
                        />
                    </div>

                    <!-- Email -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Email</label
                        >
                        <input
                            type="email"
                            v-model="form.email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="email@supplier.com"
                        />
                    </div>

                    <!-- No. Telepon -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >No. Telepon</label
                        >
                        <input
                            type="tel"
                            v-model="form.phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="08xxxxxxxxxx"
                            required
                        />
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Alamat</label
                        >
                        <textarea
                            v-model="form.address"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Alamat lengkap supplier"
                            required
                        ></textarea>
                    </div>

                    <!-- Jenis Supplier -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Jenis Supplier</label
                        >
                        <select
                            v-model="form.type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                            <option value="" disabled>-- Pilih Jenis --</option>
                            <option value="manufacturer">
                                Produsen/Pabrikan
                            </option>
                            <option value="distributor">Distributor</option>
                            <option value="retailer">Retailer</option>
                            <option value="service">Jasa</option>
                        </select>
                    </div>

                    <!-- PIC (Person In Charge) -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nama PIC</label
                        >
                        <input
                            type="text"
                            v-model="form.pic_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nama kontak person"
                        />
                    </div>

                    <!-- Jabatan PIC -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Jabatan PIC</label
                        >
                        <input
                            type="text"
                            v-model="form.pic_position"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: Manager, Sales, Owner"
                        />
                    </div>

                    <!-- No. Telepon PIC -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >No. Telepon PIC</label
                        >
                        <input
                            type="tel"
                            v-model="form.pic_phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="08xxxxxxxxxx"
                        />
                    </div>

                    <!-- NPWP -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            NPWP
                            <span class="text-gray-500">(opsional)</span>
                        </label>
                        <input
                            type="text"
                            v-model="form.tax_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nomor NPWP"
                        />
                    </div>

                    <!-- Bank Account -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Rekening Bank
                            <span class="text-gray-500">(opsional)</span>
                        </label>
                        <input
                            type="text"
                            v-model="form.bank_account"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: BCA - 123456789 (a.n PT. Supplier)"
                        />
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Catatan
                            <span class="text-gray-500">(opsional)</span>
                        </label>
                        <textarea
                            v-model="form.notes"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Catatan tambahan tentang supplier"
                        ></textarea>
                    </div>

                    <!-- Upload Logo/Photo -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Logo/Foto Supplier
                            <span class="text-gray-500">(opsional)</span>
                        </label>
                        <input
                            type="file"
                            @change="handleFileUpload"
                            accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Simpan Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </DashboardFinanceLayouts>
</template>

<script setup>
import { ref, reactive } from "vue";
import DashboardFinanceLayouts from "@/Layouts/DashboardFinanceLayouts.vue";
import { router } from "@inertiajs/vue3";

// Form state
const form = reactive({
    name: "",
    email: "",
    phone: "",
    address: "",
    type: "",
    pic_name: "",
    pic_position: "",
    pic_phone: "",
    tax_id: "",
    bank_account: "",
    notes: "",
    logo: null,
    status: "active",
});

// Handle file upload
const handleFileUpload = (event) => {
    form.logo = event.target.files[0];
};

// Submit form
const submitForm = () => {
    // Menggunakan Inertia untuk submit form dengan route name yang benar
    router.post(route("finance.suppliers.store"), form, {
        forceFormData: true,
        onSuccess: () => {
            // Redirect setelah berhasil
        },
        onError: (errors) => {
            console.error("Form errors:", errors);
        },
    });
};
</script>
