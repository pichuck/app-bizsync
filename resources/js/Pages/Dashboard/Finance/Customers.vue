<script>
import DashboardFinanceLayouts from "@/Layouts/DashboardFinanceLayouts.vue";

export default {
    name: "Customers",
    components: {
        DashboardFinanceLayouts,
    },
    props: {
        user: {
            type: Object,
            required: true,
        },
        customers: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            searchQuery: "",
            sortBy: "name",
            sortOrder: "asc",
        };
    },
    computed: {
        filteredCustomers() {
            let filtered = this.customers;

            // Filter by search query
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(
                    (customer) =>
                        customer.name.toLowerCase().includes(query) ||
                        (customer.contact || "").toLowerCase().includes(query)
                );
            }

            // Sort customers
            filtered.sort((a, b) => {
                let aValue = a[this.sortBy];
                let bValue = b[this.sortBy];

                if (this.sortBy === "totalReceivables") {
                    aValue = this.calculateTotalReceivables(a);
                    bValue = this.calculateTotalReceivables(b);
                }

                if (aValue < bValue) {
                    return this.sortOrder === "asc" ? -1 : 1;
                }
                if (aValue > bValue) {
                    return this.sortOrder === "asc" ? 1 : -1;
                }
                return 0;
            });

            return filtered;
        },
    },
    methods: {
        formatRupiah(num) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            }).format(num || 0);
        },
        calculateTotalReceivables(customer) {
            // Placeholder calculation - replace with actual logic
            return customer.total_receivables || 0;
        },
        getCreditLimitStatus(customer) {
            const receivables = this.calculateTotalReceivables(customer);
            const limit = customer.credit_limit || 0;

            if (limit === 0)
                return {
                    class: "bg-gray-100 text-gray-800",
                    text: "Tidak Ada Limit",
                };

            const percentage = (receivables / limit) * 100;

            if (percentage >= 90) {
                return {
                    class: "bg-red-100 text-red-800",
                    text: "Hampir Limit",
                };
            } else if (percentage >= 70) {
                return {
                    class: "bg-yellow-100 text-yellow-800",
                    text: "Perhatian",
                };
            } else {
                return { class: "bg-green-100 text-green-800", text: "Aman" };
            }
        },
        setSortBy(field) {
            if (this.sortBy === field) {
                this.sortOrder = this.sortOrder === "asc" ? "desc" : "asc";
            } else {
                this.sortBy = field;
                this.sortOrder = "asc";
            }
        },
        getSortIcon(field) {
            if (this.sortBy !== field) return "↕️";
            return this.sortOrder === "asc" ? "↑" : "↓";
        },
        handleCreateCustomer() {
            // Handle create customer action
            this.$inertia.visit(route("customers.create"));
        },
        handleViewCustomer(customerId) {
            // Handle view customer action
            this.$inertia.visit(route("customers.show", customerId));
        },
        handleEditCustomer(customerId) {
            // Handle edit customer action
            this.$inertia.visit(route("customers.edit", customerId));
        },
        handleDeleteCustomer(customer) {
            if (
                confirm(`Yakin ingin menghapus pelanggan "${customer.name}"?`)
            ) {
                this.$inertia.delete(route("customers.destroy", customer.id));
            }
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
                            Daftar Pelanggan
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Kelola informasi pelanggan dan pantau piutang mereka
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <button
                            @click="handleCreateCustomer"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                        >
                            + Tambah Pelanggan
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
                        placeholder="Cari nama pelanggan atau kontak..."
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

            <!-- Customers Table -->
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                    @click="setSortBy('name')"
                                >
                                    <div class="flex items-center gap-1">
                                        Nama
                                        <span class="text-gray-400">{{
                                            getSortIcon("name")
                                        }}</span>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                    @click="setSortBy('contact')"
                                >
                                    <div class="flex items-center gap-1">
                                        Kontak
                                        <span class="text-gray-400">{{
                                            getSortIcon("contact")
                                        }}</span>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                    @click="setSortBy('credit_limit')"
                                >
                                    <div class="flex items-center gap-1">
                                        Limit Kredit
                                        <span class="text-gray-400">{{
                                            getSortIcon("credit_limit")
                                        }}</span>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                    @click="setSortBy('totalReceivables')"
                                >
                                    <div class="flex items-center gap-1">
                                        Total Piutang
                                        <span class="text-gray-400">{{
                                            getSortIcon("totalReceivables")
                                        }}</span>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Status Kredit
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
                                v-for="customer in filteredCustomers"
                                :key="customer.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div
                                                class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center"
                                            >
                                                <span
                                                    class="text-sm font-medium text-blue-800"
                                                >
                                                    {{
                                                        customer.name
                                                            .charAt(0)
                                                            .toUpperCase()
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div
                                                class="font-medium text-gray-900"
                                            >
                                                {{ customer.name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ customer.contact || "-" }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ formatRupiah(customer.credit_limit) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600"
                                >
                                    {{
                                        formatRupiah(
                                            calculateTotalReceivables(customer)
                                        )
                                    }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                        :class="
                                            getCreditLimitStatus(customer).class
                                        "
                                    >
                                        {{
                                            getCreditLimitStatus(customer).text
                                        }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    <div class="flex gap-2">
                                        <button
                                            @click="
                                                handleViewCustomer(customer.id)
                                            "
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
                                            @click="
                                                handleEditCustomer(customer.id)
                                            "
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
                                            @click="
                                                handleDeleteCustomer(customer)
                                            "
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
                        v-if="filteredCustomers.length === 0"
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
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            ></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            Tidak ada pelanggan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{
                                searchQuery
                                    ? "Tidak ada pelanggan yang sesuai dengan pencarian Anda"
                                    : "Mulai dengan menambahkan pelanggan pertama"
                            }}
                        </p>
                        <div class="mt-6" v-if="!searchQuery">
                            <button
                                @click="handleCreateCustomer"
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
                                Tambah Pelanggan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardFinanceLayouts>
</template>
