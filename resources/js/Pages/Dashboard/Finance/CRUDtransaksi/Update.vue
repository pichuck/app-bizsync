<template>
    <DashboardFinanceLayouts>
        <div class="container mx-auto px-4 py-8 max-w-3xl">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Edit Transaksi
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
                            @change="handleTypeChange"
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
                            >{{
                                form.type === "sale"
                                    ? "Pelanggan"
                                    : form.type === "purchase"
                                    ? "Pemasok"
                                    : "Kontak"
                            }}</label
                        >

                        <!-- Dropdown untuk memilih kontak yang sudah ada -->
                        <div
                            v-if="
                                form.type === 'sale' || form.type === 'purchase'
                            "
                        >
                            <select
                                v-model="form.contact_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 mb-2"
                            >
                                <option value="" selected>
                                    -- Pilih
                                    {{
                                        form.type === "sale"
                                            ? "Pelanggan"
                                            : "Pemasok"
                                    }}
                                    --
                                </option>
                                <option
                                    v-for="contact in contactsList"
                                    :key="contact.id"
                                    :value="contact.id"
                                >
                                    {{ contact.name }}
                                    {{
                                        contact.phone
                                            ? `(${contact.phone})`
                                            : ""
                                    }}
                                </option>
                            </select>

                            <div class="flex items-center mt-2">
                                <span class="text-sm text-gray-500">atau</span>
                            </div>
                        </div>

                        <div class="relative mt-2">
                            <input
                                type="text"
                                v-model="contactQuery"
                                @input="fetchContacts"
                                @blur="updateContactName"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ketik nama kontak baru..."
                                autocomplete="off"
                            />

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

                    <!-- Item Transaksi -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Item Transaksi
                        </label>
                        <div class="space-y-4">
                            <div
                                v-for="(item, index) in form.items"
                                :key="index"
                                class="p-4 border border-gray-200 rounded-md"
                            >
                                <div class="flex justify-between mb-2">
                                    <h4 class="font-medium">
                                        Item #{{ index + 1 }}
                                    </h4>
                                    <button
                                        type="button"
                                        @click="removeItem(index)"
                                        class="text-red-600 hover:text-red-800"
                                        v-if="form.items.length > 1"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            ></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Deskripsi Item -->
                                <div class="mb-3">
                                    <label
                                        class="block text-xs font-medium text-gray-700 mb-1"
                                        >Deskripsi</label
                                    >
                                    <input
                                        type="text"
                                        v-model="item.description"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        required
                                        placeholder="Nama/deskripsi item"
                                    />
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <!-- Quantity -->
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 mb-1"
                                            >Jumlah</label
                                        >
                                        <input
                                            type="number"
                                            v-model="item.quantity"
                                            min="1"
                                            step="1"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            required
                                        />
                                    </div>

                                    <!-- Price -->
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 mb-1"
                                            >Harga Satuan</label
                                        >
                                        <input
                                            type="number"
                                            v-model="item.price"
                                            min="0"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            required
                                        />
                                    </div>
                                </div>

                                <!-- Subtotal -->
                                <div class="mt-3 text-right">
                                    <label
                                        class="text-xs font-medium text-gray-500"
                                        >Subtotal:</label
                                    >
                                    <span class="ml-2 font-medium">
                                        {{
                                            formatRupiah(
                                                item.quantity * item.price
                                            )
                                        }}
                                    </span>
                                </div>
                            </div>

                            <button
                                type="button"
                                @click="addItem"
                                class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-md transition-colors text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                            >
                                + Tambah Item
                            </button>

                            <!-- Total Harga -->
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="flex justify-between">
                                    <span class="font-medium">Total:</span>
                                    <span class="font-bold text-lg">
                                        {{ formatRupiah(calculatedTotal) }}
                                    </span>
                                </div>
                            </div>
                        </div>
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
import { ref, reactive, computed, watch, onMounted } from "vue";
import DashboardFinanceLayouts from "@/Layouts/DashboardFinanceLayouts.vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";

// Definisikan props
const props = defineProps({
    transaction: {
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
    date: "",
    type: "",
    contact_id: "",
    contact_name: "",
    description: "",
    total: 0,
    payment_method: "cash",
    due_date: "",
    payment_proof: null,
    items: [],
    _method: "put",
});

// UI states
const contactQuery = ref("");
const contactSuggestions = ref([]);
const showDueDate = ref(false);
const contactsList = ref([]);

// Helper untuk memformat uang
const formatRupiah = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

// Computed properties
const calculatedTotal = computed(() => {
    return form.items.reduce((total, item) => {
        return total + item.quantity * item.price;
    }, 0);
});

// Watch for calculated total changes
watch(calculatedTotal, (newTotal) => {
    form.total = newTotal;
});

// Populate form with transaction data on mount
onMounted(async () => {
    // Populate form fields
    form.date = props.transaction.date;
    form.type = props.transaction.type;
    form.contact_id = props.transaction.contact_id;
    form.contact_name = props.transaction.contact_name || "";
    form.description = props.transaction.description;
    form.total = props.transaction.total;
    form.payment_method = props.transaction.payment_method;
    form.due_date = props.transaction.due_date || "";

    // Populate items if available
    if (props.transaction.items && props.transaction.items.length) {
        form.items = props.transaction.items.map((item) => ({
            description: item.description,
            quantity: item.quantity,
            price: item.price,
        }));
    } else {
        form.items = [
            {
                description: "",
                quantity: 1,
                price: 0,
            },
        ];
    }

    // Fetch contacts for dropdown
    await fetchContacts();
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
    form.contact_name = contact.name; // Gunakan nama kontak langsung
    contactQuery.value = contact.name;
    contactSuggestions.value = [];
};

// Update contact name when input is blurred
const updateContactName = () => {
    const contact = contactSuggestions.value.find(
        (c) => c.name === contactQuery.value
    );
    if (contact) {
        form.contact_id = contact.id;
    } else {
        form.contact_id = null;
    }
};

// Handle transaction type change
const handleTypeChange = () => {
    // Reset contact and due date fields
    form.contact_id = null;
    form.contact_name = "";
    form.due_date = "";
    showDueDate.value = false;

    // Fetch contacts for the selected type
    fetchContacts();
};

// Add new item to the transaction
const addItem = () => {
    form.items.push({
        description: "",
        quantity: 1,
        price: 0,
    });
};

// Remove item from the transaction
const removeItem = (index) => {
    form.items.splice(index, 1);
};

// Submit form
const submitForm = () => {
    // Update contact_name jika diisi manual
    form.contact_name = contactQuery.value;

    router.post(
        route("finance.transactions.update", props.transaction.id),
        form,
        {
            forceFormData: true,
            onSuccess: () => {
                // Success handled by controller
            },
            onError: (err) => {
                console.error("Form errors:", err);
            },
        }
    );
};
</script>
