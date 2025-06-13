<template>
    <DashboardFinanceLayouts>
        <div class="container mx-auto px-4 py-8 max-w-3xl">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Edit Pelanggan
                </h2>

                <form @submit.prevent="submitForm" class="space-y-5">
                    <!-- Nama -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nama *</label
                        >
                        <input
                            type="text"
                            v-model="form.name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Kontak -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Kontak</label
                        >
                        <input
                            type="text"
                            v-model="form.contact"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nomor telepon / email"
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
                            placeholder="email@contoh.com"
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
                        ></textarea>
                    </div>

                    <!-- Jenis Pelanggan -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Jenis Pelanggan</label
                        >
                        <select
                            v-model="form.type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="individual">Perorangan</option>
                            <option value="company">Perusahaan</option>
                        </select>
                    </div>

                    <!-- Limit Kredit -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Limit Kredit</label
                        >
                        <input
                            type="number"
                            v-model="form.credit_limit"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            min="0"
                        />
                    </div>

                    <!-- Status -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Status</label
                        >
                        <select
                            v-model="form.status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>

                    <!-- Upload Foto -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Foto Profil
                            <span class="text-gray-500">(opsional)</span>
                        </label>
                        <input
                            type="file"
                            @change="handleFileUpload"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 flex space-x-4">
                        <button
                            type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Update Pelanggan
                        </button>
                        <Link
                            :href="route('finance.customers')"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md shadow-sm transition duration-200 text-center"
                        >
                            Batal
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </DashboardFinanceLayouts>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import DashboardFinanceLayouts from "@/Layouts/DashboardFinanceLayouts.vue";
import { router, Link } from "@inertiajs/vue3";

// Definisikan props
const props = defineProps({
    customer: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
});

// Form state
const form = reactive({
    name: "",
    contact: "",
    email: "",
    address: "",
    credit_limit: 0,
    type: "individual",
    status: "active",
    profile_image: null,
});

// Populate form dengan data customer saat komponen dimount
onMounted(() => {
    form.name = props.customer.name;
    form.contact = props.customer.contact;
    form.email = props.customer.email;
    form.address = props.customer.address;
    form.credit_limit = props.customer.credit_limit || 0;
    form.type = props.customer.type || "individual";
    form.status = props.customer.status || "active";
});

// Handle file upload
const handleFileUpload = (event) => {
    form.profile_image = event.target.files[0];
};

// Submit form
const submitForm = () => {
    router.put(route("finance.customers.update", props.customer.id), form, {
        forceFormData: true,
        onSuccess: () => {
            // Redirect akan dihandle oleh controller
        },
        onError: (errors) => {
            console.error("Form errors:", errors);
        },
    });
};
</script>
