<template>
    <div class="topbar">
        <div class="topbar-left">
            <h4 class="page-title">Dashboard Keuangan</h4>
            <p class="page-subtitle">
                Kelola keuangan bisnis Anda dengan mudah
            </p>
        </div>
        <div class="topbar-right">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input
                    type="text"
                    class="form-control search-input"
                    placeholder="Cari transaksi, pelanggan..."
                />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TopbarFinance",
};
</script>

<style scoped>
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    background: white;
    border-bottom: 1px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    width: 100%;
    min-height: 80px;
    /* Removed sticky positioning */
}

.topbar-left .page-title {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: #2c3e50;
}

.topbar-left .page-subtitle {
    margin: 0;
    font-size: 14px;
    color: #6c757d;
    margin-top: 2px;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.search-box {
    position: relative;
    width: 300px;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 2;
}

.search-input {
    width: 100%;
    padding: 10px 12px 10px 35px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-icon {
    width: 40px;
    height: 40px;
    border: none;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.3s ease;
    color: #6c757d;
}

.btn-icon:hover {
    background: #e9ecef;
    color: #495057;
}

.notification-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: #dc3545;
    color: white;
    font-size: 10px;
    padding: 2px 5px;
    border-radius: 10px;
    min-width: 16px;
    text-align: center;
}

@media (max-width: 768px) {
    .topbar {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
        padding: 15px 20px;
    }

    .topbar-right {
        justify-content: space-between;
    }

    .search-box {
        width: 100%;
        max-width: 250px;
    }
}
</style>
