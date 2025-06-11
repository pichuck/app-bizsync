<script>
import DashboardFinanceLayouts from "@/Layouts/DashboardFinanceLayouts.vue";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

export default {
    layout: DashboardFinanceLayouts,
    data() {
        return {
            incomeExpenseChart: null,
            trendChart: null,
            selectedYear: "2024",
            selectedPeriod: "monthly",
            // Data untuk berbagai periode
            trendData: {
                monthly: {
                    2024: {
                        labels: [
                            "Jul 2024",
                            "Agu 2024",
                            "Sep 2024",
                            "Okt 2024",
                            "Nov 2024",
                            "Des 2024",
                        ],
                        income: [
                            4200000, 4500000, 4800000, 5100000, 4900000,
                            5000000,
                        ],
                        expense: [
                            2800000, 2500000, 2200000, 2400000, 2100000,
                            2300000,
                        ],
                    },
                    2023: {
                        labels: [
                            "Jul 2023",
                            "Agu 2023",
                            "Sep 2023",
                            "Okt 2023",
                            "Nov 2023",
                            "Des 2023",
                        ],
                        income: [
                            3800000, 4100000, 4400000, 4700000, 4500000,
                            4600000,
                        ],
                        expense: [
                            2600000, 2300000, 2000000, 2200000, 1900000,
                            2100000,
                        ],
                    },
                },
                yearly: {
                    2024: {
                        labels: ["Q1 2024", "Q2 2024", "Q3 2024", "Q4 2024"],
                        income: [12600000, 13500000, 14700000, 15000000],
                        expense: [7800000, 7200000, 6600000, 6800000],
                    },
                    2023: {
                        labels: ["Q1 2023", "Q2 2023", "Q3 2023", "Q4 2023"],
                        income: [11400000, 12300000, 13200000, 13700000],
                        expense: [7200000, 6900000, 6300000, 6200000],
                    },
                },
            },
        };
    },
    mounted() {
        this.initCharts();
    },
    beforeUnmount() {
        if (this.incomeExpenseChart) {
            this.incomeExpenseChart.destroy();
        }
        if (this.trendChart) {
            this.trendChart.destroy();
        }
    },
    computed: {
        currentTrendData() {
            return this.trendData[this.selectedPeriod][this.selectedYear];
        },
        availableYears() {
            return Object.keys(this.trendData[this.selectedPeriod]);
        },
    },
    methods: {
        initCharts() {
            this.createIncomeExpenseChart();
            this.createTrendChart();
        },
        createIncomeExpenseChart() {
            const ctx = this.$refs.incomeExpenseChart.getContext("2d");

            this.incomeExpenseChart = new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: ["Pemasukan", "Pengeluaran", "Keuntungan"],
                    datasets: [
                        {
                            data: [5000000, 2300000, 2700000],
                            backgroundColor: [
                                "rgba(34, 197, 94, 0.8)",
                                "rgba(239, 68, 68, 0.8)",
                                "rgba(59, 130, 246, 0.8)",
                            ],
                            borderColor: [
                                "rgba(34, 197, 94, 1)",
                                "rgba(239, 68, 68, 1)",
                                "rgba(59, 130, 246, 1)",
                            ],
                            borderWidth: 2,
                            hoverOffset: 10,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.parsed;
                                    return (
                                        context.label +
                                        ": Rp " +
                                        value.toLocaleString("id-ID")
                                    );
                                },
                            },
                        },
                    },
                    cutout: "60%",
                },
            });
        },
        createTrendChart() {
            const ctx = this.$refs.trendChart.getContext("2d");
            const data = this.currentTrendData;

            this.trendChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: "Pemasukan",
                            data: data.income,
                            borderColor: "rgba(34, 197, 94, 1)",
                            backgroundColor: "rgba(34, 197, 94, 0.1)",
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: "rgba(34, 197, 94, 1)",
                            pointBorderColor: "#ffffff",
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                        },
                        {
                            label: "Pengeluaran",
                            data: data.expense,
                            borderColor: "rgba(239, 68, 68, 1)",
                            backgroundColor: "rgba(239, 68, 68, 0.1)",
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: "rgba(239, 68, 68, 1)",
                            pointBorderColor: "#ffffff",
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            mode: "index",
                            intersect: false,
                            callbacks: {
                                label: function (context) {
                                    return (
                                        context.dataset.label +
                                        ": Rp " +
                                        context.parsed.y.toLocaleString("id-ID")
                                    );
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    weight: "500",
                                },
                                color: "#6b7280",
                            },
                        },
                        y: {
                            grid: {
                                color: "rgba(107, 114, 128, 0.1)",
                            },
                            ticks: {
                                callback: function (value) {
                                    return "Rp " + value / 1000000 + "M";
                                },
                                font: {
                                    size: 12,
                                },
                                color: "#6b7280",
                            },
                        },
                    },
                    interaction: {
                        mode: "nearest",
                        axis: "x",
                        intersect: false,
                    },
                },
            });
        },
        updateTrendChart() {
            if (this.trendChart) {
                this.trendChart.destroy();
            }
            this.createTrendChart();
        },
        onPeriodChange() {
            this.updateTrendChart();
        },
        onYearChange() {
            this.updateTrendChart();
        },
    },
};
</script>

<template>
    <DashboardFinanceLayouts>
        <div class="p-6">
            <!-- Stats Cards Row -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
            >
                <!-- Total Pemasukan -->
                <div
                    class="group relative bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden"
                >
                    <!-- Accent Border -->
                    <div
                        class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-green-500 to-emerald-400"
                    ></div>

                    <!-- Icon -->
                    <div
                        class="absolute top-4 right-4 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform duration-300"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                            ></path>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="relative z-10">
                        <p class="text-gray-600 text-sm font-medium mb-2">
                            Total Pemasukan
                        </p>
                        <h3 class="text-2xl font-bold text-green-600 mb-3">
                            Rp 5.000.000
                        </h3>
                        <div
                            class="flex items-center text-green-600 text-sm font-semibold"
                        >
                            <svg
                                class="w-4 h-4 mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 11l5-5m0 0l5 5m-5-5v12"
                                ></path>
                            </svg>
                            +15.3%
                        </div>
                    </div>

                    <!-- Shine Effect -->
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"
                    ></div>
                </div>

                <!-- Total Pengeluaran -->
                <div
                    class="group relative bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden"
                >
                    <!-- Accent Border -->
                    <div
                        class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-500 to-rose-400"
                    ></div>

                    <!-- Icon -->
                    <div
                        class="absolute top-4 right-4 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform duration-300"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"
                            ></path>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="relative z-10">
                        <p class="text-gray-600 text-sm font-medium mb-2">
                            Total Pengeluaran
                        </p>
                        <h3 class="text-2xl font-bold text-red-600 mb-3">
                            Rp 2.300.000
                        </h3>
                        <div
                            class="flex items-center text-red-600 text-sm font-semibold"
                        >
                            <svg
                                class="w-4 h-4 mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 13l-5 5m0 0l-5-5m5 5V6"
                                ></path>
                            </svg>
                            -8.2%
                        </div>
                    </div>

                    <!-- Shine Effect -->
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"
                    ></div>
                </div>

                <!-- Piutang Belum Lunas -->
                <div
                    class="group relative bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden"
                >
                    <!-- Accent Border -->
                    <div
                        class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-yellow-500 to-amber-400"
                    ></div>

                    <!-- Icon -->
                    <div
                        class="absolute top-4 right-4 w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600 group-hover:scale-110 transition-transform duration-300"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="relative z-10">
                        <p class="text-gray-600 text-sm font-medium mb-2">
                            Piutang Belum Lunas
                        </p>
                        <h3 class="text-2xl font-bold text-yellow-600 mb-3">
                            Rp 1.200.000
                        </h3>
                        <div
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800"
                        >
                            12 Transaksi
                        </div>
                    </div>

                    <!-- Shine Effect -->
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"
                    ></div>
                </div>

                <!-- Utang ke Pemasok -->
                <div
                    class="group relative bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden"
                >
                    <!-- Accent Border -->
                    <div
                        class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-cyan-400"
                    ></div>

                    <!-- Icon -->
                    <div
                        class="absolute top-4 right-4 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300"
                    >
                        <svg
                            class="w-6 h-6"
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
                    </div>

                    <!-- Content -->
                    <div class="relative z-10">
                        <p class="text-gray-600 text-sm font-medium mb-2">
                            Utang ke Pemasok
                        </p>
                        <h3 class="text-2xl font-bold text-blue-600 mb-3">
                            Rp 3.500.000
                        </h3>
                        <div
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800"
                        >
                            5 Transaksi
                        </div>
                    </div>

                    <!-- Shine Effect -->
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"
                    ></div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Grafik Pemasukan vs Pengeluaran -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg border border-gray-100"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-800">
                            Pemasukan vs Pengeluaran
                        </h3>
                        <div class="flex items-center space-x-4 text-sm">
                            <div class="flex items-center">
                                <div
                                    class="w-3 h-3 bg-green-500 rounded-full mr-2"
                                ></div>
                                <span class="text-gray-600">Pemasukan</span>
                            </div>
                            <div class="flex items-center">
                                <div
                                    class="w-3 h-3 bg-red-500 rounded-full mr-2"
                                ></div>
                                <span class="text-gray-600">Pengeluaran</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <canvas
                            ref="incomeExpenseChart"
                            class="w-full h-80"
                        ></canvas>
                    </div>
                </div>

                <!-- Grafik Trend Bulanan dengan Filter -->
                <div
                    class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg border border-gray-100"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-800">
                            Trend Keuangan
                        </h3>
                        <div class="flex items-center space-x-3">
                            <!-- Filter Period -->
                            <select
                                v-model="selectedPeriod"
                                @change="onPeriodChange"
                                class="px-3 py-1 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            >
                                <option value="monthly">Bulanan</option>
                                <option value="yearly">Kuartalan</option>
                            </select>
                            <!-- Filter Year -->
                            <select
                                v-model="selectedYear"
                                @change="onYearChange"
                                class="px-3 py-1 bg-blue-100 text-blue-800 border border-blue-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            >
                                <option
                                    v-for="year in availableYears"
                                    :key="year"
                                    :value="year"
                                >
                                    {{ year }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="relative">
                        <canvas ref="trendChart" class="w-full h-80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Finansial -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg border border-gray-100"
            >
                <h3 class="text-lg font-bold text-gray-800 mb-6">
                    Ringkasan Keuangan Bisnis
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="text-center p-4 bg-green-50 rounded-xl border border-green-200"
                    >
                        <div class="text-2xl font-bold text-green-600 mb-2">
                            Rp 2.700.000
                        </div>
                        <div class="text-sm text-green-700 font-medium">
                            Keuntungan Bersih
                        </div>
                        <div class="text-xs text-green-600 mt-1">
                            Pemasukan - Pengeluaran
                        </div>
                    </div>
                    <div
                        class="text-center p-4 bg-blue-50 rounded-xl border border-blue-200"
                    >
                        <div class="text-2xl font-bold text-blue-600 mb-2">
                            54%
                        </div>
                        <div class="text-sm text-blue-700 font-medium">
                            Margin Keuntungan
                        </div>
                        <div class="text-xs text-blue-600 mt-1">
                            Persentase laba bersih
                        </div>
                    </div>
                    <div
                        class="text-center p-4 bg-purple-50 rounded-xl border border-purple-200"
                    >
                        <div class="text-2xl font-bold text-purple-600 mb-2">
                            -Rp 2.300.000
                        </div>
                        <div class="text-sm text-purple-700 font-medium">
                            Saldo Bersih
                        </div>
                        <div class="text-xs text-purple-600 mt-1">
                            Piutang - Utang
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardFinanceLayouts>
</template>

<style scoped>
/* Custom animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.group {
    animation: fadeInUp 0.6s ease-out forwards;
}

.group:nth-child(1) {
    animation-delay: 0.1s;
}
.group:nth-child(2) {
    animation-delay: 0.2s;
}
.group:nth-child(3) {
    animation-delay: 0.3s;
}
.group:nth-child(4) {
    animation-delay: 0.4s;
}

/* Pulse effect for numbers */
.group:hover h3 {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%,
    100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

/* Select dropdown styling */
select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}
</style>
