<template>
    <DashboardFinanceLayouts>
        <div class="p-6 bg-gray-50 min-h-screen">
            <div class="max-w-4xl mx-auto">
                <!-- Header with breadcrumb and actions -->
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <div
                            class="flex items-center space-x-2 text-sm text-gray-500 mb-2"
                        >
                            <Link
                                :href="route('finance.suppliers')"
                                class="hover:text-blue-600"
                                >Pemasok</Link
                            >
                            <span>
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </span>
                            <span>Detail Pemasok #{{ supplier.id }}</span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Detail Pemasok
                        </h1>
                    </div>
                    <div class="flex space-x-3">
                        <Link
                            :href="route('finance.suppliers.edit', supplier.id)"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors inline-flex items-center"
                        >
                            <svg
                                class="h-4 w-4 mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                />
                            </svg>
                            Edit
                        </Link>
                        <button
                            @click="printSupplierData"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors inline-flex items-center"
                        >
                            <svg
                                class="h-4 w-4 mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                                />
                            </svg>
                            Cetak
                        </button>
                    </div>
                </div>

                <!-- Supplier Card -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6"
                >
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div
                                    :class="[
                                        getSupplierTypeBackgroundClass,
                                        'p-2 rounded-full mr-4',
                                    ]"
                                >
                                    <component
                                        :is="getSupplierTypeIcon"
                                        class="h-8 w-8 text-white"
                                    />
                                </div>
                                <div>
                                    <h2
                                        class="text-xl font-semibold text-gray-800"
                                    >
                                        {{ supplier.name }}
                                    </h2>
                                    <p class="text-gray-600">
                                        {{ getSupplierTypeLabel }} •
                                        {
                                            supplier.status === "active"
                                                ? "Aktif"
                                                : "Non-Aktif"
                                        }}
                                    </p>
                                </div>
                            </div>
                            <span
                                :class="[
                                    getStatusClass,
                                    'px-4 py-1 rounded-full text-sm font-medium',
                                ]"
                            >
                                {{ getStatusText }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Kontak
                                </p>
                                <p class="text-lg font-medium text-gray-900">
                                    {{ supplier.phone || "-" }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Email
                                </p>
                                <p class="text-lg font-medium text-gray-900">
                                    {{ supplier.email || "-" }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Alamat
                                </p>
                                <p class="text-lg font-medium text-gray-900">
                                    {{ supplier.address || "-" }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Kerjasama Sejak
                                </p>
                                <p class="text-lg font-medium text-gray-900">
                                    {{ formatDate(supplier.created_at) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PIC Info -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden"
                >
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Informasi PIC
                        </h3>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- PIC Name -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Nama PIC
                                </p>
                                <p class="text-xl font-medium text-gray-900">
                                    {{ supplier.pic_name || "-" }}
                                </p>
                            </div>

                            <!-- PIC Position -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Jabatan
                                </p>
                                <p class="text-xl font-medium text-gray-900">
                                    {{ supplier.pic_position || "-" }}
                                </p>
                            </div>

                            <!-- PIC Phone -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    No. Telepon PIC
                                </p>
                                <p class="text-xl font-medium text-gray-900">
                                    {{ supplier.pic_phone || "-" }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Info -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden"
                >
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Informasi Keuangan
                        </h3>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Total Spent -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Total Pembelian
                                </p>
                                <p class="text-xl font-bold text-gray-900">
                                    {{ formatRupiah(totalSpent) }}
                                </p>
                            </div>

                            <!-- Outstanding Payments -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Pembayaran Tertunda
                                </p>
                                <p
                                    :class="[
                                        'text-xl font-bold',
                                        outstandingBalance > 0
                                            ? 'text-red-600'
                                            : 'text-gray-900',
                                    ]"
                                >
                                    {{ formatRupiah(outstandingBalance) }}
                                </p>
                            </div>

                            <!-- Average Order Value -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Rata-rata Nilai Transaksi
                                </p>
                                <p class="text-xl font-bold text-gray-900">
                                    {{ formatRupiah(averageOrderValue) }}
                                </p>
                            </div>
                        </div>

                        <!-- Bank Account -->
                        <div class="mt-6 bg-gray-50 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-500 mb-1">
                                Rekening Bank
                            </p>
                            <p class="text-gray-900">
                                {{ supplier.bank_account || "-" }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden"
                >
                    <div
                        class="p-6 border-b border-gray-200 flex justify-between items-center"
                    >
                        <h3 class="text-lg font-medium text-gray-900">
                            Transaksi Terbaru
                        </h3>
                        <Link
                            :href="
                                route('finance.transactions', {
                                    supplier_id: supplier.id,
                                })
                            "
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                        >
                            Lihat Semua
                        </Link>
                    </div>

                    <div class="p-6">
                        <div
                            v-if="
                                supplier.transactions &&
                                supplier.transactions.length > 0
                            "
                        >
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Tanggal
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Deskripsi
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                No. Referensi
                                            </th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Jumlah
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Status
                                            </th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr
                                            v-for="transaction in supplier.transactions"
                                            :key="transaction.id"
                                        >
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"
                                            >
                                                {{
                                                    formatDate(transaction.date)
                                                }}
                                            </td>
                                            <td
                                                class="px-4 py-3 text-sm text-gray-900"
                                            >
                                                {{ transaction.description }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"
                                            >
                                                {{ transaction.reference_no || "-" }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-red-600"
                                            >
                                                {{
                                                    formatRupiah(
                                                        transaction.total
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-center"
                                            >
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full"
                                                    :class="
                                                        getTransactionStatusClass(
                                                            transaction
                                                        )
                                                    "
                                                >
                                                    {{
                                                        getTransactionStatusText(
                                                            transaction
                                                        )
                                                    }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-right space-x-2"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'finance.transactions.show',
                                                            transaction.id
                                                        )
                                                    "
                                                    class="text-blue-600 hover:text-blue-900"
                                                >
                                                    Detail
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div v-else class="text-center py-8 text-gray-500">
                            Pemasok ini belum memiliki transaksi.
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6"
                >
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Informasi Tambahan
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NPWP -->
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    NPWP
                                </p>
                                <p class="text-gray-900">
                                    {{ supplier.tax_id || "-" }}
                                </p>
                            </div>

                            <!-- Product Categories -->
                            <div v-if="supplier.product_categories">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Kategori Produk
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="(category, i) in supplier.product_categories"
                                        :key="i"
                                        class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs"
                                    >
                                        {{ category }}
                                    </span>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="col-span-full" v-if="supplier.notes">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Catatan
                                </p>
                                <div
                                    class="bg-gray-50 p-4 rounded-md text-gray-700"
                                >
                                    {{ supplier.notes }}
                                </div>
                            </div>
                        </div>

                        <!-- Logo Image -->
                        <div class="mt-6" v-if="supplier.logo_url">
                            <p class="text-sm font-medium text-gray-500 mb-2">
                                Logo Perusahaan
                            </p>
                            <img
                                :src="supplier.logo_url"
                                alt="Supplier Logo"
                                class="h-32 w-auto object-contain rounded-lg border border-gray-200"
                            />
                        </div>
                    </div>
                </div>

                <!-- Activity History -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200"
                >
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Riwayat Aktivitas
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <li
                                    v-for="(event, index) in supplierHistory"
                                    :key="index"
                                >
                                    <div class="relative pb-8">
                                        <span
                                            class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200"
                                            aria-hidden="true"
                                            v-if="
                                                index !==
                                                supplierHistory.length - 1
                                            "
                                        ></span>
                                        <div
                                            class="relative flex items-start space-x-3"
                                        >
                                            <div>
                                                <div
                                                    :class="[
                                                        event.iconBg,
                                                        'h-10 w-10 rounded-full flex items-center justify-center',
                                                    ]"
                                                >
                                                    <component
                                                        :is="event.icon"
                                                        class="h-5 w-5 text-white"
                                                    />
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ event.title }}
                                                </p>
                                                <p
                                                    class="mt-0.5 text-sm text-gray-500"
                                                >
                                                    {{ event.description }}
                                                    <span
                                                        class="font-medium"
                                                        v-if="event.user"
                                                        >oleh
                                                        {{ event.user }}</span
                                                    >
                                                </p>
                                            </div>
                                            <div
                                                class="text-right text-sm whitespace-nowrap text-gray-500"
                                            >
                                                {{ formatDate(event.date) }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardFinanceLayouts>
</template>

<script setup>
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import DashboardFinanceLayouts from "@/Layouts/DashboardFinanceLayouts.vue";

// Props untuk menerima data supplier
const props = defineProps({
    supplier: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
});

// Icon components
const FactoryIcon = {
    template: `
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
    </svg>
  `,
};

const TruckIcon = {
    template: `
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
    </svg>
  `,
};

const StoreIcon = {
    template: `
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
    </svg>
  `,
};

const ServiceIcon = {
    template: `
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
    </svg>
  `,
};

const CheckCircleIcon = {
    template: `
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
  `,
};

const ClockIcon = {
    template: `
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
  `,
};

const ReceiptIcon = {
    template: `
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
    </svg>
  `,
};

const PencilIcon = {
    template: `
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
    </svg>
  `,
};

// Computed properties
const getSupplierTypeLabel = computed(() => {
    const types = {
        manufacturer: "Produsen/Pabrikan",
        distributor: "Distributor",
        retailer: "Retailer",
        service: "Jasa",
    };
    return types[props.supplier.type] || props.supplier.type;
});

const getSupplierTypeIcon = computed(() => {
    const icons = {
        manufacturer: FactoryIcon,
        distributor: TruckIcon,
        retailer: StoreIcon,
        service: ServiceIcon,
    };
    return icons[props.supplier.type] || FactoryIcon;
});

const getSupplierTypeBackgroundClass = computed(() => {
    const backgrounds = {
        manufacturer: "bg-purple-500",
        distributor: "bg-blue-500",
        retailer: "bg-green-500",
        service: "bg-orange-500",
    };
    return backgrounds[props.supplier.type] || "bg-gray-500";
});

const totalSpent = computed(() => {
    if (!props.supplier.transactions || !props.supplier.transactions.length) {
        return 0;
    }
    return props.supplier.transactions.reduce(
        (total, transaction) => total + (transaction.total || 0),
        0
    );
});

const outstandingBalance = computed(() => {
    if (!props.supplier.transactions || !props.supplier.transactions.length) {
        return 0;
    }
    return props.supplier.transactions
        .filter((t) => t.status === "unpaid")
        .reduce((total, transaction) => total + (transaction.total || 0), 0);
});

const averageOrderValue = computed(() => {
    if (!props.supplier.transactions || !props.supplier.transactions.length) {
        return 0;
    }
    return totalSpent.value / props.supplier.transactions.length;
});

const getStatusClass = computed(() => {
    if (props.supplier.status !== "active") return "bg-red-100 text-red-800";
    return "bg-green-100 text-green-800";
});

const getStatusText = computed(() => {
    return props.supplier.status === "active" ? "Aktif" : "Non-Aktif";
});

// Helper functions
const formatDate = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const formatRupiah = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

const getTransactionStatusClass = (transaction) => {
    if (
        transaction.payment_method === "cash" ||
        transaction.status === "paid"
    ) {
        return "bg-green-100 text-green-800";
    }
    return "bg-yellow-100 text-yellow-800";
};

const getTransactionStatusText = (transaction) => {
    if (transaction.payment_method === "cash") {
        return "Lunas (Cash)";
    }
    return transaction.status === "paid" ? "Lunas" : "Belum Lunas";
};

// Supplier history mocked data - dalam aplikasi sebenarnya akan datang dari API
const supplierHistory = [
    {
        title: "Pemasok Terdaftar",
        description: "Pemasok baru ditambahkan ke sistem",
        user: "Admin Finance",
        date: props.supplier.created_at || "2024-01-01",
        icon: FactoryIcon,
        iconBg: "bg-blue-500",
    },
    {
        title: "Kontrak Diperbarui",
        description: "Syarat dan ketentuan kontrak diperbarui",
        user: "Admin Finance",
        date: props.supplier.updated_at || "2024-01-05",
        icon: CheckCircleIcon,
        iconBg: "bg-green-500",
    },
    // Tambahkan transaksi terakhir jika ada
    ...(props.supplier.transactions && props.supplier.transactions.length > 0
        ? [
              {
                  title: "Transaksi Terakhir",
                  description: props.supplier.transactions[0].description,
                  user: "Admin Finance",
                  date: props.supplier.transactions[0].date,
                  icon: ReceiptIcon,
                  iconBg: "bg-purple-500",
              },
          ]
        : []),
];

// Action functions
const printSupplierData = () => {
    window.print();
};
</script>
