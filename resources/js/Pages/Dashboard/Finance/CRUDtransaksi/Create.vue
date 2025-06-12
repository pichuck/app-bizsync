<template>
    <DashboardFinanceLayouts>
        <div class="container mx-auto px-4 py-8 max-w-3xl">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Tambah Transaksi
                </h2>

                <form @submit.prevent="submitForm" class="space-y-5">
                    <!-- Tanggal Transaksi -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Tanggal Transaksi</label
                        >
                        <input
                            type="date"
                            v-model="form.date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Jenis Transaksi -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Jenis Transaksi</label
                        >
                        <select
                            v-model="form.type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                            <option value="" disabled>-- Pilih Jenis --</option>
                            <option value="sale">Penjualan</option>
                            <option value="purchase">Pembelian</option>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>

                    <!-- Pelanggan/Pemasok -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Pelanggan / Pemasok</label
                        >
                        <div class="relative">
                            <input
                                type="text"
                                v-model="contactQuery"
                                @input="fetchContacts"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ketik nama..."
                                autocomplete="off"
                            />
                            <input type="hidden" v-model="form.contact_id" />

                            <!-- Suggestions Dropdown -->
                            <div
                                v-if="contactSuggestions.length > 0"
                                class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-200 max-h-60 overflow-y-auto"
                            >
                                <div
                                    v-for="contact in contactSuggestions"
                                    :key="contact.id"
                                    @click="selectContact(contact)"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer transition-colors"
                                >
                                    {{ contact.name }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Deskripsi</label
                        >
                        <input
                            type="text"
                            v-model="form.description"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                            placeholder="Contoh: Pembelian barang dari Supplier A"
                        />
                    </div>

                    <!-- Total Nominal -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Total Nominal</label
                        >
                        <input
                            type="number"
                            v-model="form.total"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Metode Pembayaran</label
                        >
                        <select
                            v-model="form.payment_method"
                            @change="toggleDueDate"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                            <option value="cash">Cash</option>
                            <option value="credit">Kredit</option>
                        </select>
                    </div>

                    <!-- Jatuh Tempo -->
                    <div v-if="showDueDate">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Jatuh Tempo</label
                        >
                        <input
                            type="date"
                            v-model="form.due_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>

                    <!-- Upload Bukti -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Upload Bukti Pembayaran
                            <span class="text-gray-500">(opsional)</span>
                        </label>
                        <input
                            type="file"
                            @change="handleFileUpload"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Simpan Transaksi
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

// Form state
const form = reactive({
    date: new Date().toISOString().split("T")[0],
    type: "",
    contact_id: "",
    description: "",
    total: 0,
    payment_method: "cash",
    due_date: "",
    payment_proof: null,
});

// UI states
const contactQuery = ref("");
const contactSuggestions = ref([]);
const showDueDate = ref(false);

// Toggle due date visibility
const toggleDueDate = () => {
    showDueDate.value = form.payment_method === "credit";
};

// Handle file upload
const handleFileUpload = (event) => {
    form.payment_proof = event.target.files[0];
};

// Fetch contacts from API
const fetchContacts = async () => {
    if (contactQuery.value.length < 2 || !form.type) {
        contactSuggestions.value = [];
        return;
    }

    try {
        const response = await fetch(
            `/api/contacts/search?type=${form.type}&q=${contactQuery.value}`
        );
        contactSuggestions.value = await response.json();
    } catch (error) {
        console.error("Error fetching contacts:", error);
        contactSuggestions.value = [];
    }
};

// Select contact from suggestions
const selectContact = (contact) => {
    form.contact_id = contact.id;
    contactQuery.value = contact.name;
    contactSuggestions.value = [];
};

// Submit form
const submitForm = () => {
    // Implement form submission logic here
    console.log("Form submitted:", form);

    // Example using FormData for file upload
    const formData = new FormData();
    for (const key in form) {
        formData.append(key, form[key]);
    }

    // Axios example:
    // axios.post('/transactions', formData, {
    //   headers: { 'Content-Type': 'multipart/form-data' }
    // })
};
</script>
