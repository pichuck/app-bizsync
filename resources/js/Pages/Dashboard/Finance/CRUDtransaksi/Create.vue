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
                        <div
                            v-if="errors.date"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.date }}
                        </div>
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
                            @change="clearContactSelection"
                        >
                            <option value="" disabled>-- Pilih Jenis --</option>
                            <option value="sale">Penjualan</option>
                            <option value="purchase">Pembelian</option>
                            <option value="income">Pendapatan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                        <div
                            v-if="errors.type"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.type }}
                        </div>
                    </div>

                    <!-- Pelanggan/Pemasok Dropdown -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >{{ getContactTypeLabel }}</label
                        >
                        <select
                            v-model="form.contact_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            :disabled="!form.type"
                        >
                            <option value="">
                                -- Pilih {{ getContactTypeLabel }} --
                            </option>

                            <!-- Debug: Tambahkan keterangan jumlah data -->
                            <option
                                value=""
                                disabled
                                v-if="form.type === 'sale'"
                            >
                                {{ customers.length }} customers tersedia
                            </option>
                            <option
                                value=""
                                disabled
                                v-else-if="form.type === 'purchase'"
                            >
                                {{ suppliers.length }} suppliers tersedia
                            </option>
                            <option
                                value=""
                                disabled
                                v-else-if="
                                    ['income', 'expense'].includes(form.type)
                                "
                            >
                                {{ contacts.length }} contacts tersedia
                            </option>

                            <!-- Opsi untuk Penjualan (Customers) -->
                            <template v-if="form.type === 'sale'">
                                <option
                                    v-for="customer in customers"
                                    :key="'customer-' + customer.id"
                                    :value="customer.id"
                                >
                                    {{ customer.name }}
                                    {{
                                        customer.phone
                                            ? `- ${customer.phone}`
                                            : ""
                                    }}
                                </option>
                            </template>

                            <!-- Opsi untuk Pembelian (Suppliers) -->
                            <template v-else-if="form.type === 'purchase'">
                                <option
                                    v-for="supplier in suppliers"
                                    :key="'supplier-' + supplier.id"
                                    :value="supplier.id"
                                >
                                    {{ supplier.name }}
                                    {{
                                        supplier.phone
                                            ? `- ${supplier.phone}`
                                            : ""
                                    }}
                                </option>
                            </template>

                            <!-- Opsi untuk Pendapatan/Pengeluaran (Contacts) -->
                            <template
                                v-else-if="
                                    ['income', 'expense'].includes(form.type)
                                "
                            >
                                <option
                                    v-for="contact in contacts"
                                    :key="'contact-' + contact.id"
                                    :value="contact.id"
                                >
                                    {{ contact.name }}
                                    {{
                                        contact.phone
                                            ? `- ${contact.phone}`
                                            : ""
                                    }}
                                </option>
                            </template>
                        </select>
                        <div
                            v-if="errors.contact_id"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.contact_id }}
                        </div>
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
                        <div
                            v-if="errors['items.0.description']"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors["items.0.description"] }}
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Deskripsi Transaksi</label
                        >
                        <input
                            type="text"
                            v-model="form.description"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                            placeholder="Contoh: Pembelian barang dari Supplier A"
                        />
                        <div
                            v-if="errors.description"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.description }}
                        </div>
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
                        <div
                            v-if="errors.payment_method"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.payment_method }}
                        </div>
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
                        <div
                            v-if="errors.due_date"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.due_date }}
                        </div>
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
                            placeholder="Catatan tambahan tentang transaksi ini"
                        ></textarea>
                        <div
                            v-if="errors.notes"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.notes }}
                        </div>
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
                            accept="image/*,.pdf"
                        />
                        <div
                            v-if="errors.payment_proof"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.payment_proof }}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="isSubmitting"
                        >
                            <span v-if="isSubmitting">
                                <svg
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                Sedang Menyimpan...
                            </span>
                            <span v-else>Simpan Transaksi</span>
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

// Pastikan props menerima data contacts dengan benar
const props = defineProps({
    customers: {
        type: Array,
        default: () => [],
    },
    suppliers: {
        type: Array,
        default: () => [],
    },
    contacts: {
        type: Array,
        default: () => [],
    },
    user: {
        type: Object,
        required: true,
    },
});

// Debug untuk memeriksa data yang diterima
console.log("Customers:", props.customers);
console.log("Suppliers:", props.suppliers);
console.log("Contacts:", props.contacts);

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
    notes: "",
    items: [
        {
            description: "",
            quantity: 1,
            price: 0,
        },
    ],
});

// UI states
const showDueDate = ref(false);
const isSubmitting = ref(false);
const errors = ref({});

// Computed properties
const getContactTypeLabel = computed(() => {
    const types = {
        sale: "Pelanggan",
        purchase: "Pemasok",
        income: "Sumber Pendapatan",
        expense: "Penerima Pembayaran",
    };
    return types[form.type] || "Kontak";
});

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

// Clear contact selection when changing transaction type
const clearContactSelection = () => {
    form.contact_id = "";
};

// Handle file upload
const handleFileUpload = (event) => {
    form.payment_proof = event.target.files[0];
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

// Format currency
const formatRupiah = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

// Submit form
const submitForm = () => {
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

// Pastikan props menerima data contacts dengan benar
onMounted(() => {
    console.log("Component mounted, checking data:");
    console.log("Customers length:", props.customers?.length || 0);
    console.log("Contacts length:", props.contacts?.length || 0);
});
</script>
