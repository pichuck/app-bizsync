<template>
<div class="sidebar">
        <div class="text-center profile-section px-3">
            <img src="{{ asset('images/profile.jpg') }}" alt="Profile">
            <div>Welcome Back</div>
            <strong>Admin Baik Hati</strong>
        </div>
        <ul class="nav flex-column mt-4">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
            <i class="bi bi-arrow-left-right me-2"></i> Transaksi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
            <i class="bi bi-people me-2"></i> Pelanggan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
            <i class="bi bi-truck me-2"></i> Pemasok
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
            <i class="bi bi-bar-chart-line me-2"></i> Laporan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
            <i class="bi bi-wallet2 me-2"></i> Pembayaran
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}" href="{{ route('audit-logs.index') }}">
            <i class="bi bi-shield-lock me-2"></i> Audit Log
        </a>
    </li>
</ul>


        <div class="mt-auto px-3">
            <button class="btn btn-outline-light w-100">Logout</button>
        </div>
    </div>



</template>

<style scoped>

body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f8fc;
        }
        .sidebar {
            width: 230px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #1e3a5f;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px 0;
        }
        .sidebar .profile-section img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .sidebar .nav-link {
            color: #e0e6ed;
            padding: 10px 20px;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #34557d;
            color: #ffffff;
            border-radius: 10px;
        }
    </style>

