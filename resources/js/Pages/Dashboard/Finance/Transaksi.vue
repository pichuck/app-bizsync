<script>
import DashboardFinanceLayouts from "@/Layouts/DashboardFinanceLayouts.vue";

export default {
    name: "Transaksi",
    components: {
        DashboardFinanceLayouts,
    },
    props: {
        user: {
            type: Object,
            required: true,
        },
        transactions: {
            type: Array,
            default: () => [],
        },
        summary: {
            type: Object,
            default: () => ({}),
        },
    },
    data() {
        return {
            filterType: "",
            searchQuery: "",
            dateFrom: "",
            dateTo: "",
            showFilters: false,
        };
    },
    computed: {
        filteredTransactions() {
            let filtered = this.transactions;

            // Filter by type
            if (this.filterType) {
                filtered = filtered.filter(
                    (trx) => trx.type === this.filterType
                );
            }

            // Filter by search query
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(
                    (trx) =>
                        trx.description.toLowerCase().includes(query) ||
                        (trx.contact?.name || "").toLowerCase().includes(query)
                );
            }

            // Filter by date range
            if (this.dateFrom || this.dateTo) {
                filtered = filtered.filter((trx) => {
                    const trxDate = new Date(trx.date);
                    const fromDate = this.dateFrom
                        ? new Date(this.dateFrom)
                        : null;
                    const toDate = this.dateTo ? new Date(this.dateTo) : null;

                    if (fromDate && trxDate < fromDate) return false;
                    if (toDate && trxDate > toDate) return false;
                    return true;
                });
            }

            return filtered;
        },
        hasActiveFilters() {
            return (
                this.filterType ||
                this.searchQuery ||
                this.dateFrom ||
                this.dateTo
            );
        },
    },
    methods: {
        formatDate(date) {
            const d = new Date(date);
            return d.toLocaleDateString("id-ID", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },
        formatRupiah(num) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            }).format(num);
        },
        getStatusBadge(transaction) {
            if (transaction.payment_method === "cash") {
                return { class: "bg-green-100 text-green-800", text: "Lunas" };
            }
            return transaction.status === "paid"
                ? { class: "bg-green-100 text-green-800", text: "Lunas" }
                : {
                      class: "bg-yellow-100 text-yellow-800",
                      text: "Belum Lunas",
                  };
        },
        getTypeLabel(type) {
            const labels = {
                sale: "Penjualan",
                purchase: "Pembelian",
                income: "Pendapatan",
                expense: "Pengeluaran",
            };
            return labels[type] || type;
        },
        getTypeIcon(type) {
            const icons = {
                sale: "bi-cart-check",
                purchase: "bi-bag",
                income: "bi-arrow-up-circle",
                expense: "bi-arrow-down-circle",
            };
            return icons[type] || "bi-receipt";
        },
        getTypeColor(type) {
            const colors = {
                sale: "bg-blue-100 text-blue-800",
                purchase: "bg-purple-100 text-purple-800",
                income: "bg-green-100 text-green-800",
                expense: "bg-red-100 text-red-800",
            };
            return colors[type] || "bg-gray-100 text-gray-800";
        },
        clearFilters() {
            this.filterType = "";
            this.searchQuery = "";
            this.dateFrom = "";
            this.dateTo = "";
        },
        toggleFilters() {
            this.showFilters = !this.showFilters;
        },
    },
};
</script>

<template>
    <DashboardFinanceLayouts :user="user">
        <div class="p-6 bg-gray-50 min-h-screen">
            <!-- Header Section -->
            <div class="mb-6">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                >
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Daftar Transaksi
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Kelola dan pantau semua aktivitas keuangan bisnis
                            Anda
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <select
                            v-model="filterType"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">-- Semua Jenis --</option>
                            <option value="sale">Penjualan</option>
                            <option value="purchase">Pembelian</option>
                            <option value="income">Pendapatan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                        <button
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                        >
                            + Tambah Transaksi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <div class="relative">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari..."
                        class="w-full max-w-md px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                    <svg
                        class="absolute left-3 top-2.5 h-5 w-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        ></path>
                    </svg>
                </div>
            </div>

            <!-- Transaction Table -->
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Tanggal
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Deskripsi
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Customer/Pemasok
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Total
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="trx in filteredTransactions"
                                :key="trx.id"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ formatDate(trx.date) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                            :class="getTypeColor(trx.type)"
                                        >
                                            {{ getTypeLabel(trx.type) }}
                                        </span>
                                        <span>{{ trx.description }}</span>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ trx.contact?.name || "-" }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                    :class="
                                        ['sale', 'income'].includes(trx.type)
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                >
                                    {{ formatRupiah(trx.total) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                        :class="getStatusBadge(trx).class"
                                    >
                                        {{ getStatusBadge(trx).text }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    <div class="flex gap-2">
                                        <button
                                            class="text-blue-600 hover:text-blue-900 p-1"
                                            title="Detail"
                                        >
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                ></path>
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                ></path>
                                            </svg>
                                        </button>
                                        <button
                                            class="text-yellow-600 hover:text-yellow-900 p-1"
                                            title="Edit"
                                        >
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                ></path>
                                            </svg>
                                        </button>
                                        <button
                                            class="text-red-600 hover:text-red-900 p-1"
                                            title="Hapus"
                                        >
                                            <svg
                                                class="w-4 h-4"
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
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Empty State -->
                    <div
                        v-if="filteredTransactions.length === 0"
                        class="text-center py-12"
                    >
                        <svg
                            class="mx-auto h-12 w-12 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            ></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            Tidak ada transaksi.
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{
                                hasActiveFilters
                                    ? "Coba ubah filter pencarian Anda"
                                    : "Mulai dengan menambahkan transaksi pertama"
                            }}
                        </p>
                        <div class="mt-6" v-if="!hasActiveFilters">
                            <button
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
                            >
                                <svg
                                    class="-ml-1 mr-2 h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    ></path>
                                </svg>
                                Tambah Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardFinanceLayouts>
</template>
