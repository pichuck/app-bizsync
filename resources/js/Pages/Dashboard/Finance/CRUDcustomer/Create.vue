<template>
    <DashboardFinanceLayouts>
        <div class="container mx-auto px-4 py-8 max-w-3xl">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Tambah Pelanggan
                </h2>

                <form @submit.prevent="submitForm" class="space-y-5">
                    <!-- Nama Pelanggan -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nama Pelanggan</label
                        >
                        <input
                            type="text"
                            v-model="form.name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nama lengkap pelanggan atau perusahaan"
                            required
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
                            placeholder="email@perusahaan.com"
                        />
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nomor Telepon</label
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
                            placeholder="Alamat lengkap"
                            required
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
                            required
                        >
                            <option value="" disabled>-- Pilih Jenis --</option>
                            <option value="individual">Perorangan</option>
                            <option value="company">Perusahaan</option>
                        </select>
                    </div>

                    <!-- NPWP (Opsional) -->
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

                    <!-- PIC (Jika Perusahaan) -->
                    <div v-if="form.type === 'company'">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nama PIC</label
                        >
                        <input
                            type="text"
                            v-model="form.pic_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nama person in charge"
                        />
                    </div>

                    <!-- Nomor PIC (Jika Perusahaan) -->
                    <div v-if="form.type === 'company'">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nomor Telepon PIC</label
                        >
                        <input
                            type="tel"
                            v-model="form.pic_phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nomor telepon person in charge"
                        />
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Catatan
                            <span class="text-gray-500">(opsional)</span>
                        </label>
                        <textarea
                            v-model="form.notes"
                            rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Catatan tambahan"
                        ></textarea>
                    </div>

                    <!-- Upload Foto (Opsional) -->
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
                            accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                    </div>

                    <!-- Credit Limit -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Batas Kredit (Rp)
                            <span class="text-gray-500">(opsional)</span>
                        </label>
                        <input
                            type="number"
                            v-model="form.credit_limit"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Batas kredit untuk pelanggan ini"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Simpan Pelanggan
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
import axios from "axios";

// Form state
const form = reactive({
    name: "",
    email: "",
    phone: "",
    address: "",
    type: "",
    tax_id: "",
    pic_name: "",
    pic_phone: "",
    notes: "",
    profile_image: null,
    credit_limit: null,
    status: "active",
});

// UI states
const contactQuery = ref("");
const contactSuggestions = ref([]);
const showDueDate = ref(false);
const isSubmitting = ref(false);
const errors = ref({});
const isLoadingContacts = ref(false);

// Computed properties
const calculatedTotal = computed(() => {
    return form.items.reduce((total, item) => {
        return total + item.quantity * item.price;
    }, 0);
});

// Watch for calculated total changes and update form total
watch(calculatedTotal, (newTotal) => {
    form.total = newTotal;
});

// Toggle due date visibility
const toggleDueDate = () => {
    showDueDate.value = form.payment_method === "credit";
    if (!showDueDate.value) {
        form.due_date = "";
    }
};

// Handle file upload
const handleFileUpload = (event) => {
    form.profile_image = event.target.files[0];
};

// Modifikasi function untuk contact input
const fetchContacts = async () => {
    if (contactQuery.value.length < 2 || !form.type) {
        contactSuggestions.value = [];
        return;
    }

    try {
        isLoadingContacts.value = true;
        // Coba gunakan API jika tersedia
        const response = await axios.get("/api/contacts/search", {
            params: {
                type: form.type,
                q: contactQuery.value,
            },
        });
        contactSuggestions.value = response.data;
    } catch (error) {
        console.error("Error fetching contacts:", error);
        contactSuggestions.value = [];
    } finally {
        isLoadingContacts.value = false;
    }
};

// Modifikasi function untuk select contact
const selectContact = (contact) => {
    form.contact_name = contact.name; // Simpan contact name, bukan ID
    contactQuery.value = contact.name;
    contactSuggestions.value = [];
};

// Jika user mengetik langsung tanpa memilih dari sugesti
const updateContactName = () => {
    form.contact_name = contactQuery.value;
};

// Add new item row
const addItem = () => {
    form.items.push({
        description: "",
        quantity: 1,
        price: 0,
    });
};

// Remove item row
const removeItem = (index) => {
    if (form.items.length > 1) {
        form.items.splice(index, 1);
    }
};

// Submit form
const submitForm = () => {
    // Update contact_name jika diisi manual
    form.contact_name = contactQuery.value;

    isSubmitting.value = true;
    errors.value = {};

    router.post(route("finance.transactions.store"), form, {
        forceFormData: true,
        onSuccess: () => {
            isSubmitting.value = false;
            // Redirect handled by controller
        },
        onError: (err) => {
            isSubmitting.value = false;
            errors.value = err;
            console.error("Form errors:", err);
        },
    });
};
</script>
