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
                                :href="route('finance.transactions')"
                                class="hover:text-blue-600"
                                >Transaksi</Link
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
                            <span>Detail Transaksi #{{ transaction.id }}</span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Detail Transaksi
                        </h1>
                    </div>
                    <div class="flex space-x-3">
                        <Link
                            :href="
                                route(
                                    'finance.transactions.edit',
                                    transaction.id
                                )
                            "
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
                            @click="printInvoice"
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

                <!-- Status Card -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6"
                >
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div
                                    :class="[
                                        getStatusBackgroundClass,
                                        'p-2 rounded-full mr-4',
                                    ]"
                                >
                                    <component
                                        :is="getStatusIcon"
                                        class="h-8 w-8 text-white"
                                    />
                                </div>
                                <div>
                                    <h2
                                        class="text-xl font-semibold text-gray-800"
                                    >
                                        {{ getTypeLabel }}
                                    </h2>
                                    <p class="text-gray-600">
                                        {{ transaction.description }}
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
                                    Tanggal Transaksi
                                </p>
                                <p class="text-lg font-medium text-gray-900">
                                    {{ formatDate(transaction.date) }}
                                </p>
                            </div>
                            <div
                                v-if="
                                    transaction.payment_method === 'credit' &&
                                    transaction.due_date
                                "
                            >
                                <p class="text-sm font-medium text-gray-500">
                                    Jatuh Tempo
                                </p>
                                <p
                                    class="text-lg font-medium"
                                    :class="
                                        isPastDue
                                            ? 'text-red-600'
                                            : 'text-gray-900'
                                    "
                                >
                                    {{ formatDate(transaction.due_date) }}
                                    <span
                                        v-if="isPastDue"
                                        class="text-sm text-red-600 ml-2"
                                        >(Terlambat)</span
                                    >
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Metode Pembayaran
                                </p>
                                <div class="flex items-center mt-1">
                                    <svg
                                        class="h-5 w-5 text-gray-400 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                        />
                                    </svg>
                                    <span class="text-gray-900 capitalize">
                                        {{
                                            transaction.payment_method ===
                                            "cash"
                                                ? "Tunai"
                                                : "Kredit"
                                        }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Status Pembayaran
                                </p>
                                <span
                                    :class="[
                                        getStatusClass,
                                        'mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                                    ]"
                                >
                                    {{ getStatusText }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Details -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden"
                >
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Detail Transaksi
                        </h3>
                    </div>

                    <div class="p-6">
                        <div
                            class="flex flex-col md:flex-row md:justify-between mb-6"
                        >
                            <div class="mb-4 md:mb-0">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    {{ transactionPartnerType }}
                                </p>
                                <p class="text-gray-900 font-medium">
                                    {{ transaction.contact?.name }}
                                </p>
                                <p
                                    class="text-gray-500 text-sm mt-1"
                                    v-if="transaction.contact?.address"
                                >
                                    {{ transaction.contact?.address }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    Jumlah Transaksi
                                </p>
                                <p
                                    :class="[
                                        'text-2xl font-bold',
                                        getAmountTextColorClass,
                                    ]"
                                >
                                    {{ formatRupiah(transaction.total) }}
                                </p>
                            </div>
                        </div>

                        <!-- Items List if available -->
                        <div
                            v-if="
                                transaction.items &&
                                transaction.items.length > 0
                            "
                            class="mt-8"
                        >
                            <h4
                                class="text-base font-medium text-gray-900 mb-4"
                            >
                                Item
                            </h4>
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Deskripsi
                                            </th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Jumlah
                                            </th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Harga
                                            </th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in transaction.items"
                                            :key="index"
                                        >
                                            <td
                                                class="px-4 py-3 text-sm text-gray-900"
                                            >
                                                {{ item.description }}
                                            </td>
                                            <td
                                                class="px-4 py-3 text-sm text-gray-900 text-right"
                                            >
                                                {{ item.quantity }}
                                            </td>
                                            <td
                                                class="px-4 py-3 text-sm text-gray-900 text-right"
                                            >
                                                {{ formatRupiah(item.price) }}
                                            </td>
                                            <td
                                                class="px-4 py-3 text-sm text-gray-900 text-right"
                                            >
                                                {{
                                                    formatRupiah(
                                                        item.quantity *
                                                            item.price
                                                    )
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="border-t border-gray-200">
                                        <tr>
                                            <td
                                                colspan="3"
                                                class="px-4 py-3 text-sm font-medium text-gray-900 text-right"
                                            >
                                                Total
                                            </td>
                                            <td
                                                class="px-4 py-3 text-sm font-bold text-gray-900 text-right"
                                            >
                                                {{
                                                    formatRupiah(
                                                        transaction.total
                                                    )
                                                }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes & Attachments -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6"
                >
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Catatan & Lampiran
                        </h3>
                    </div>
                    <div class="p-6">
                        <!-- Notes -->
                        <div class="mb-6" v-if="transaction.notes">
                            <h4
                                class="text-base font-medium text-gray-900 mb-2"
                            >
                                Catatan
                            </h4>
                            <div
                                class="bg-gray-50 p-4 rounded-md text-gray-700"
                            >
                                {{ transaction.notes }}
                            </div>
                        </div>

                        <!-- Attachment -->
                        <div v-if="transaction.payment_proof">
                            <h4
                                class="text-base font-medium text-gray-900 mb-2"
                            >
                                Bukti Pembayaran
                            </h4>
                            <div class="border border-gray-200 rounded-md p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg
                                            class="h-8 w-8 text-blue-500 mr-3"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                            />
                                        </svg>
                                        <div>
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                Bukti_Pembayaran.pdf
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                PDF • Diunggah pada
                                                {{
                                                    formatDate(
                                                        transaction.updated_at
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <a
                                        href="#"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                    >
                                        Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction History -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200"
                >
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Riwayat Transaksi
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <li
                                    v-for="(event, index) in transactionHistory"
                                    :key="index"
                                >
                                    <div class="relative pb-8">
                                        <span
                                            class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200"
                                            aria-hidden="true"
                                            v-if="
                                                index !==
                                                transactionHistory.length - 1
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

// Props untuk menerima data transaksi
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

// Icon components
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
const getTypeLabel = computed(() => {
    const labels = {
        sale: "Penjualan",
        purchase: "Pembelian",
        income: "Pendapatan",
        expense: "Pengeluaran",
    };
    return labels[props.transaction.type] || props.transaction.type;
});

const getStatusIcon = computed(() => {
    if (
        props.transaction.status === "paid" ||
        props.transaction.payment_method === "cash"
    ) {
        return CheckCircleIcon;
    }
    return ClockIcon;
});

const getStatusBackgroundClass = computed(() => {
    const types = {
        sale: "bg-blue-500",
        purchase: "bg-purple-500",
        income: "bg-green-500",
        expense: "bg-red-500",
    };
    return types[props.transaction.type] || "bg-gray-500";
});

const getStatusClass = computed(() => {
    if (
        props.transaction.status === "paid" ||
        props.transaction.payment_method === "cash"
    ) {
        return "bg-green-100 text-green-800";
    }
    return "bg-yellow-100 text-yellow-800";
});

const getStatusText = computed(() => {
    if (props.transaction.payment_method === "cash") {
        return "Lunas (Cash)";
    }
    return props.transaction.status === "paid" ? "Lunas" : "Belum Lunas";
});

const getAmountTextColorClass = computed(() => {
    if (["sale", "income"].includes(props.transaction.type)) {
        return "text-green-600";
    }
    return "text-red-600";
});

const transactionPartnerType = computed(() => {
    if (["sale", "income"].includes(props.transaction.type)) {
        return "Pelanggan";
    }
    return "Pemasok";
});

const isPastDue = computed(() => {
    if (!props.transaction.due_date) return false;
    const dueDate = new Date(props.transaction.due_date);
    const today = new Date();
    return dueDate < today && props.transaction.status !== "paid";
});

// Format functions
const formatDate = (dateString) => {
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

// Transaction history mocked data - in real app would come from API
const transactionHistory = [
    {
        title: "Transaksi Dibuat",
        description: "Transaksi dibuat",
        user: "Admin Finance",
        date: props.transaction.created_at || props.transaction.date,
        icon: ReceiptIcon,
        iconBg: "bg-blue-500",
    },
    {
        title:
            props.transaction.status === "paid" ||
            props.transaction.payment_method === "cash"
                ? "Pembayaran Diterima"
                : "Menunggu Pembayaran",
        description:
            props.transaction.status === "paid" ||
            props.transaction.payment_method === "cash"
                ? "Pembayaran telah diterima"
                : "Menunggu konfirmasi pembayaran",
        user:
            props.transaction.status === "paid" ||
            props.transaction.payment_method === "cash"
                ? "Admin Finance"
                : null,
        date:
            props.transaction.status === "paid"
                ? props.transaction.updated_at
                : props.transaction.date,
        icon:
            props.transaction.status === "paid" ||
            props.transaction.payment_method === "cash"
                ? CheckCircleIcon
                : ClockIcon,
        iconBg:
            props.transaction.status === "paid" ||
            props.transaction.payment_method === "cash"
                ? "bg-green-500"
                : "bg-yellow-500",
    },
];

// Action functions
const printInvoice = () => {
    window.print();
};
</script>
