<template>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <SidebarFinance :user="user" />

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Header/Topbar -->
            <HeaderFinance :user="user" />

            <!-- Content Area -->
            <div class="content-area">
                <slot />
            </div>
        </div>
    </div>
</template>

<script>
import SidebarFinance from "@/Components/SidebarFinance.vue";
import HeaderFinance from "@/Components/HeaderFinance.vue";

export default {
    name: "DashboardFinanceLayouts",
    components: {
        SidebarFinance,
        HeaderFinance,
    },
    props: {
        user: {
            type: Object,
            default: () => ({}),
        },
    },
};
</script>

<style scoped>
.dashboard-wrapper {
    display: flex;
    min-height: 100vh;
    background-color: #f8f9fa;
}

.main-content {
    flex: 1;
    margin-left: 230px; /* Width of sidebar */
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.content-area {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }

    .content-area {
        padding: 15px;
    }
}
</style>
