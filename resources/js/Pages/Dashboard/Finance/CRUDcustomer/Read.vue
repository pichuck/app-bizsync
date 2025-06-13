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
                                :href="route('finance.customers')"
                                class="hover:text-blue-600"
                                >Pelanggan</Link
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
                            <span>Detail Pelanggan #{{ customer.id }}</span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Detail Pelanggan
                        </h1>
                    </div>
                    <div class="flex space-x-3">
                        <Link
                            :href="route('finance.customers.edit', customer.id)"
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
                            @click="printCustomerData"
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

                <!-- Customer Card -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6"
                >
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div
                                    :class="[
                                        getCustomerTypeBackgroundClass,
                                        'p-2 rounded-full mr-4',
                                    ]"
                                >
                                    <component
                                        :is="getCustomerTypeIcon"
                                        class="h-8 w-8 text-white"
                                    />
                                </div>
                                <div>
                                    <h2
                                        class="text-xl font-semibold text-gray-800"
                                    >
                                        {{ customer.name }}
                                    </h2>
                                    <p class="text-gray-600">
                                        {{ getCustomerTypeLabel }} •
                                        {{
                                            customer.status === "active"
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
                                {{ getCreditStatusText }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Kontak
                                </p>
                                <p class="text-lg font-medium text-gray-900">
                                    {{
                                        customer.contact ||
                                        customer.phone ||
                                        "-"
                                    }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Email
                                </p>
                                <p class="text-lg font-medium text-gray-900">
                                    {{ customer.email || "-" }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Alamat
                                </p>
                                <p class="text-lg font-medium text-gray-900">
                                    {{ customer.address || "-" }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Bergabung Sejak
                                </p>
                                <p class="text-lg font-medium text-gray-900">
                                    {{ formatDate(customer.created_at) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credit & Financial Info -->
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
                            <!-- Credit Limit -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Limit Kredit
                                </p>
                                <p class="text-xl font-bold text-gray-900">
                                    {{
                                        formatRupiah(customer.credit_limit || 0)
                                    }}
                                </p>
                            </div>

                            <!-- Outstanding Balance -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Saldo Terhutang
                                </p>
                                <p
                                    :class="[
                                        'text-xl font-bold',
                                        getOutstandingBalanceColorClass,
                                    ]"
                                >
                                    {{
                                        formatRupiah(
                                            customer.outstanding_balance || 0
                                        )
                                    }}
                                </p>
                            </div>

                            <!-- Credit Available -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Sisa Kredit
                                </p>
                                <p
                                    :class="[
                                        'text-xl font-bold',
                                        getCreditAvailableColorClass,
                                    ]"
                                >
                                    {{ formatRupiah(creditAvailable) }}
                                </p>
                            </div>
                        </div>

                        <!-- Credit utilization progress bar -->
                        <div class="mt-6" v-if="customer.credit_limit">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-medium text-gray-700">
                                    Penggunaan Kredit
                                </p>
                                <p class="text-sm font-medium text-gray-700">
                                    {{
                                        Math.round(creditUtilizationPercentage)
                                    }}%
                                </p>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div
                                    class="h-2.5 rounded-full"
                                    :class="[creditUtilizationColorClass]"
                                    :style="{
                                        width: `${creditUtilizationPercentage}%`,
                                    }"
                                ></div>
                            </div>
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
                                    customer_id: customer.id,
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
                                customer.transactions &&
                                customer.transactions.length > 0
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
                                                Jenis
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
                                            v-for="transaction in customer.transactions"
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
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full"
                                                    :class="
                                                        getTransactionTypeClass(
                                                            transaction.type
                                                        )
                                                    "
                                                >
                                                    {{
                                                        getTransactionTypeLabel(
                                                            transaction.type
                                                        )
                                                    }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium"
                                                :class="
                                                    getTransactionAmountClass(
                                                        transaction.type
                                                    )
                                                "
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
                            Pelanggan ini belum memiliki transaksi.
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
                            <div v-if="customer.tax_id">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    NPWP
                                </p>
                                <p class="text-gray-900">
                                    {{ customer.tax_id }}
                                </p>
                            </div>

                            <!-- PIC Info for company -->
                            <div
                                v-if="
                                    customer.type === 'company' &&
                                    customer.pic_name
                                "
                            >
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Nama PIC
                                </p>
                                <p class="text-gray-900">
                                    {{ customer.pic_name }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 mt-1"
                                    v-if="customer.pic_phone"
                                >
                                    {{ customer.pic_phone }}
                                </p>
                            </div>

                            <!-- Notes -->
                            <div class="col-span-full" v-if="customer.notes">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Catatan
                                </p>
                                <div
                                    class="bg-gray-50 p-4 rounded-md text-gray-700"
                                >
                                    {{ customer.notes }}
                                </div>
                            </div>
                        </div>

                        <!-- Profile Image -->
                        <div class="mt-6" v-if="customer.profile_image">
                            <p class="text-sm font-medium text-gray-500 mb-2">
                                Foto Profil
                            </p>
                            <img
                                :src="customer.profile_image"
                                alt="Profile"
                                class="h-32 w-32 object-cover rounded-lg border border-gray-200"
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
                                    v-for="(event, index) in customerHistory"
                                    :key="index"
                                >
                                    <div class="relative pb-8">
                                        <span
                                            class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200"
                                            aria-hidden="true"
                                            v-if="
                                                index !==
                                                customerHistory.length - 1
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

// Props untuk menerima data customer
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

// Icon components
const UserIcon = {
    template: `
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
    </svg>
  `,
};

const BuildingIcon = {
    template: `
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
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
const getCustomerTypeLabel = computed(() => {
    return props.customer.type === "company" ? "Perusahaan" : "Perorangan";
});

const getCustomerTypeIcon = computed(() => {
    return props.customer.type === "company" ? BuildingIcon : UserIcon;
});

const getCustomerTypeBackgroundClass = computed(() => {
    return props.customer.type === "company" ? "bg-purple-500" : "bg-blue-500";
});

const creditAvailable = computed(() => {
    const limit = props.customer.credit_limit || 0;
    const outstanding = props.customer.outstanding_balance || 0;
    return Math.max(0, limit - outstanding);
});

const creditUtilizationPercentage = computed(() => {
    if (!props.customer.credit_limit) return 0;
    const percentage =
        ((props.customer.outstanding_balance || 0) /
            props.customer.credit_limit) *
        100;
    return Math.min(100, percentage); // Cap at 100%
});

const creditUtilizationColorClass = computed(() => {
    const percentage = creditUtilizationPercentage.value;
    if (percentage > 80) return "bg-red-500";
    if (percentage > 60) return "bg-yellow-500";
    return "bg-green-500";
});

const getStatusClass = computed(() => {
    if (!props.customer.credit_limit) return "bg-gray-100 text-gray-800";

    const percentage = creditUtilizationPercentage.value;
    if (percentage > 80) return "bg-red-100 text-red-800";
    if (percentage > 60) return "bg-yellow-100 text-yellow-800";
    return "bg-green-100 text-green-800";
});

const getCreditStatusText = computed(() => {
    if (!props.customer.credit_limit) return "Tanpa Kredit";

    const percentage = creditUtilizationPercentage.value;
    if (percentage > 80) return "Kredit Hampir Habis";
    if (percentage > 60) return "Kredit Terbatas";
    return "Kredit Baik";
});

const getOutstandingBalanceColorClass = computed(() => {
    return (props.customer.outstanding_balance || 0) > 0
        ? "text-red-600"
        : "text-gray-900";
});

const getCreditAvailableColorClass = computed(() => {
    return creditAvailable.value > 0 ? "text-green-600" : "text-gray-900";
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

const getTransactionTypeLabel = (type) => {
    const labels = {
        sale: "Penjualan",
        purchase: "Pembelian",
        income: "Pendapatan",
        expense: "Pengeluaran",
    };
    return labels[type] || type;
};

const getTransactionTypeClass = (type) => {
    const classes = {
        sale: "bg-blue-100 text-blue-800",
        purchase: "bg-purple-100 text-purple-800",
        income: "bg-green-100 text-green-800",
        expense: "bg-red-100 text-red-800",
    };
    return classes[type] || "bg-gray-100 text-gray-800";
};

const getTransactionAmountClass = (type) => {
    return ["sale", "income"].includes(type)
        ? "text-green-600"
        : "text-red-600";
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

// Customer history mocked data - dalam aplikasi sebenarnya akan datang dari API
const customerHistory = [
    {
        title: "Pelanggan Terdaftar",
        description: "Pelanggan baru ditambahkan ke sistem",
        user: "Admin Finance",
        date: props.customer.created_at || "2024-01-01",
        icon: UserIcon,
        iconBg: "bg-blue-500",
    },
    {
        title: "Limit Kredit Diatur",
        description: `Limit kredit diatur ke ${formatRupiah(
            props.customer.credit_limit || 0
        )}`,
        user: "Admin Finance",
        date: props.customer.updated_at || "2024-01-05",
        icon: CheckCircleIcon,
        iconBg: "bg-green-500",
    },
    // Tambahkan transaksi terakhir jika ada
    ...(props.customer.transactions && props.customer.transactions.length > 0
        ? [
              {
                  title: "Transaksi Terakhir",
                  description: props.customer.transactions[0].description,
                  user: "Admin Finance",
                  date: props.customer.transactions[0].date,
                  icon: ReceiptIcon,
                  iconBg: "bg-purple-500",
              },
          ]
        : []),
];

// Action functions
const printCustomerData = () => {
    window.print();
};
</script>
