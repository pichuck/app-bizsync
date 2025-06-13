<template>
    <div class="sidebar">
        <!-- Brand Section -->
        <div class="sidebar-brand">
            <div class="brand-logo">
                <i class="bi bi-calculator"></i>
            </div>
            <div class="brand-text">
                <h5>FinanceApp</h5>
                <small>Management System</small>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="profile-section">
            <div class="profile-avatar">
                <img src="/assets/images/user.png" alt="Profile" />
            </div>
            <div class="profile-info">
                <div class="welcome-text">Welcome Back</div>
                <strong class="admin-name">{{
                    user?.name || "Admin Finance"
                }}</strong>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <Link
                        href="/dashboard/finance"
                        class="nav-link"
                        :class="{
                            active:
                                $page.component === 'Dashboard/Finance/Index',
                        }"
                    >
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </Link>
                </li>
                <li class="nav-item">
                    <Link
                        href="/dashboard/finance/transactions"
                        class="nav-link"
                        :class="{
                            active: [
                                'Dashboard/Finance/Transaksi',
                                'Dashboard/Finance/CRUDtransaksi/Create',
                                'Dashboard/Finance/CRUDtransaksi/Update',
                            ].includes($page.component),
                        }"
                    >
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Transaksi</span>
                    </Link>
                </li>
                <li class="nav-item">
                    <Link
                        href="/dashboard/finance/customers"
                        class="nav-link"
                        :class="{
                            active: [
                                'Dashboard/Finance/Customers',
                                'Dashboard/Finance/CRUDcustomer/Create',
                                'Dashboard/Finance/CRUDcustomer/Update',
                                'Dashboard/Finance/CRUDcustomer/Read',
                            ].includes($page.component),
                        }"
                    >
                        <i class="bi bi-people"></i>
                        <span>Pelanggan</span>
                    </Link>
                </li>
                <li class="nav-item">
                    <Link
                        href="/dashboard/finance/suppliers"
                        class="nav-link"
                        :class="{
                            active: [
                                'Dashboard/Finance/Supliers',
                                'Dashboard/Finance/CRUDsupliers/Create',
                                'Dashboard/Finance/CRUDsupliers/Update',
                                'Dashboard/Finance/CRUDsupliers/Read',
                            ].includes($page.component),
                        }"
                    >
                        <i class="bi bi-truck"></i>
                        <span>Pemasok</span>
                    </Link>
                </li>
            </ul>
        </nav>

        <!-- Logout Section -->
        <div class="sidebar-footer">
            <Link
                href="/logout"
                method="post"
                as="button"
                class="btn btn-logout"
                @click="handleLogout"
            >
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </Link>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";

export default {
    name: "SidebarFinance",
    components: {
        Link,
    },
    props: {
        user: {
            type: Object,
            default: () => ({}),
        },
    },
    methods: {
        showComingSoon(feature) {
            alert(`Fitur ${feature} akan segera hadir!`);
        },
        handleLogout() {
            if (confirm("Apakah Anda yakin ingin logout?")) {
                // Inertia akan menangani logout
                return true;
            }
            return false;
        },
    },
};
</script>

<style scoped>
.sidebar {
    width: 230px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: linear-gradient(180deg, #1e3a5f 0%, #2c5282 100%);
    color: white;
    display: flex;
    flex-direction: column;
    z-index: 1000;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

/* Brand Section */
.sidebar-brand {
    display: flex;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.brand-logo {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

.brand-logo i {
    font-size: 20px;
    color: #fff;
}

.brand-text h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.brand-text small {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.7);
}

/* Profile Section */
.profile-section {
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.profile-avatar {
    position: relative;
    display: inline-block;
    margin-bottom: 12px;
}

.profile-avatar img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.2);
}

.welcome-text {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 4px;
}

.admin-name {
    font-size: 14px;
    font-weight: 600;
}

/* Navigation */
.sidebar-nav {
    flex: 1;
    padding: 20px 0;
}

.nav-item {
    margin-bottom: 4px;
}

.nav-link {
    display: flex;
    align-items: center;
    color: rgba(255, 255, 255, 0.8);
    padding: 12px 20px;
    text-decoration: none;
    transition: all 0.3s ease;
    border-radius: 0 25px 25px 0;
    margin-right: 10px;
    cursor: pointer;
}

.nav-link i {
    width: 20px;
    font-size: 16px;
    margin-right: 12px;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    transform: translateX(5px);
}

.nav-link.active {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    font-weight: 500;
    position: relative;
}

.nav-link.active::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: #fff;
}

/* Footer */
.sidebar-footer {
    padding: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-logout {
    width: 100%;
    padding: 12px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.9);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    text-decoration: none;
    cursor: pointer;
}

.btn-logout i {
    margin-right: 8px;
}

.btn-logout:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .sidebar.show {
        transform: translateX(0);
    }
}
</style>
