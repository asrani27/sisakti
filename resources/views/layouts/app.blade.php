<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - SI SAKTI')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #0f172a;
            color: #e2e8f0;
        }

        /* Sidebar styles */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 1000;
            border-right: 1px solid rgba(148, 163, 184, 0.1);
        }

        .sidebar-header {
            padding: 4px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
            flex-shrink: 0;
        }

        .sidebar-menu {
            padding: 16px 0;
            overflow-y: auto;
            flex-grow: 1;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 3px;
        }

        .sidebar-bottom {
            padding: 16px 0;
            border-top: 1px solid rgba(148, 163, 184, 0.1);
            flex-shrink: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.2) 100%);
        }

        /* User menu in sidebar bottom */
        .sidebar-user-menu {
            padding: 0 16px;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .sidebar-user-profile {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 12px;
            background: rgba(30, 41, 59, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-user-profile:hover {
            background: rgba(99, 102, 241, 0.2);
        }

        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(99, 102, 241, 0.3);
            flex-shrink: 0;
        }

        .sidebar-user-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #e2e8f0;
            margin: 0 0 2px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-info p {
            font-size: 12px;
            color: rgba(226, 232, 240, 0.6);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Notification button in top header */
        .top-notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(30, 41, 59, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(226, 232, 240, 0.6);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .top-notification-btn:hover {
            background: rgba(99, 102, 241, 0.2);
            color: #a5b4fc;
        }

        .top-notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            background: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }

        .sidebar-user-info {
            flex-grow: 0;
            min-width: 0;
        }

        /* User dropdown menu */
        .user-dropdown {
            position: absolute;
            bottom: 100%;
            left: 0;
            right: 0;
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 12px;
            margin-bottom: 8px;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(226, 232, 240, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            gap: 12px;
        }

        .user-dropdown-item:hover {
            background: rgba(99, 102, 241, 0.1);
            color: #a5b4fc;
        }

        .user-dropdown-item.danger {
            color: #fca5a5;
        }

        .user-dropdown-item.danger:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        .user-dropdown-divider {
            height: 1px;
            background: rgba(148, 163, 184, 0.2);
            margin: 4px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: rgba(226, 232, 240, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .menu-item:hover {
            background: rgba(99, 102, 241, 0.1);
            color: #a5b4fc;
        }

        .menu-item.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.2) 0%, transparent 100%);
            color: #a5b4fc;
            border-right: 3px solid #6366f1;
        }

        .menu-item.active:hover {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.3) 0%, transparent 100%);
            color: #c7d2fe;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
        }

        .menu-item i {
            width: 24px;
            margin-right: 12px;
        }

        .menu-item .chevron {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .menu-item.open .chevron {
            transform: rotate(180deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0, 0, 0, 0.2);
        }

        .submenu.open {
            max-height: 1000px;
        }

        .submenu-item {
            display: block;
            padding: 10px 24px 10px 60px;
            color: rgba(226, 232, 240, 0.6);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .submenu-item:hover {
            background: rgba(99, 102, 241, 0.1);
            color: #a5b4fc;
        }

        .submenu-item.active {
            color: #a5b4fc;
            background: rgba(99, 102, 241, 0.15);
        }

        /* Main content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background: #0f172a;
            transition: all 0.3s ease;
        }

        /* Top header */
        .top-header {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            padding: 16px 32px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 16px 10px 40px;
            background: rgba(30, 41, 59, 0.5);
            border: 2px solid rgba(148, 163, 184, 0.2);
            border-radius: 12px;
            width: 300px;
            color: #e2e8f0;
            transition: all 0.3s ease;
        }

        .search-box input::placeholder {
            color: rgba(226, 232, 240, 0.5);
        }

        .search-box input:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            background: rgba(30, 41, 59, 0.8);
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(226, 232, 240, 0.5);
        }

        /* Modal styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.98) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 16px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .modal-icon i {
            color: white;
            font-size: 24px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #e2e8f0;
            text-align: center;
            margin-bottom: 8px;
        }

        .modal-text {
            font-size: 14px;
            color: rgba(226, 232, 240, 0.7);
            text-align: center;
            margin-bottom: 24px;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
        }

        .modal-btn {
            flex: 1;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .modal-btn-cancel {
            background: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border: 2px solid rgba(148, 163, 184, 0.3);
        }

        .modal-btn-cancel:hover {
            background: rgba(30, 41, 59, 1);
            border-color: rgba(148, 163, 184, 0.5);
        }

        .modal-btn-confirm {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .modal-btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }

        /* Content area */
        .content-area {
            padding: 32px;
        }

        /* Card gradient styles */
        .card-gradient {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%);
            border: 1px solid rgba(99, 102, 241, 0.2);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3), 0 0 20px rgba(99, 102, 241, 0.1);
        }

        .card-gradient:hover {
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.4), 0 0 30px rgba(99, 102, 241, 0.15);
            border-color: rgba(99, 102, 241, 0.4);
        }

        /* Card styles for dark theme */
        .bg-white {
            background: rgba(30, 41, 59, 0.5) !important;
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        .shadow-sm {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
        }

        .text-gray-800 {
            color: #e2e8f0 !important;
        }

        .text-gray-500 {
            color: rgba(226, 232, 240, 0.6) !important;
        }

        .text-gray-700 {
            color: #cbd5e1 !important;
        }

        .text-gray-600 {
            color: rgba(226, 232, 240, 0.7) !important;
        }

        .bg-gray-50 {
            background: rgba(15, 23, 42, 0.5) !important;
        }

        .hover\:shadow-md:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4) !important;
        }

        /* Colored backgrounds for dark theme */
        .bg-indigo-100 {
            background: rgba(99, 102, 241, 0.2) !important;
        }

        .bg-purple-100 {
            background: rgba(139, 92, 246, 0.2) !important;
        }

        .bg-green-100 {
            background: rgba(34, 197, 94, 0.2) !important;
        }

        .bg-orange-100 {
            background: rgba(249, 115, 22, 0.2) !important;
        }

        .bg-blue-100 {
            background: rgba(59, 130, 246, 0.2) !important;
        }

        .bg-yellow-100 {
            background: rgba(234, 179, 8, 0.2) !important;
        }

        .bg-red-100 {
            background: rgba(239, 68, 68, 0.2) !important;
        }

        .bg-green-50 {
            background: rgba(34, 197, 94, 0.15) !important;
            border-color: rgba(34, 197, 94, 0.3) !important;
        }

        .bg-yellow-50 {
            background: rgba(234, 179, 8, 0.15) !important;
            border-color: rgba(234, 179, 8, 0.3) !important;
        }

        /* Text colors */
        .text-indigo-600 {
            color: #a5b4fc !important;
        }

        .text-purple-600 {
            color: #c4b5fd !important;
        }

        .text-green-600 {
            color: #86efac !important;
        }

        .text-orange-600 {
            color: #fdba74 !important;
        }

        .text-blue-600 {
            color: #93c5fd !important;
        }

        .text-yellow-600 {
            color: #fde047 !important;
        }

        .text-red-300 {
            color: #fca5a5 !important;
        }

        .text-red-200 {
            color: #fca5a5 !important;
        }

        .text-green-500 {
            color: #86efac !important;
        }

        .text-blue-500 {
            color: #93c5fd !important;
        }

        .text-orange-500 {
            color: #fdba74 !important;
        }

        /* Hover states */
        .hover\:bg-indigo-50:hover {
            background: rgba(99, 102, 241, 0.15) !important;
        }

        .hover\:bg-green-50:hover {
            background: rgba(34, 197, 94, 0.15) !important;
        }

        .hover\:bg-purple-50:hover {
            background: rgba(139, 92, 246, 0.15) !important;
        }

        .hover\:bg-blue-50:hover {
            background: rgba(59, 130, 246, 0.15) !important;
        }

        .hover\:bg-indigo-200:hover {
            background: rgba(99, 102, 241, 0.3) !important;
        }

        .hover\:bg-green-200:hover {
            background: rgba(34, 197, 94, 0.3) !important;
        }

        .hover\:bg-purple-200:hover {
            background: rgba(139, 92, 246, 0.3) !important;
        }

        .hover\:bg-blue-200:hover {
            background: rgba(59, 130, 246, 0.3) !important;
        }

        .hover\:border-indigo-300:hover {
            border-color: rgba(99, 102, 241, 0.4) !important;
        }

        .hover\:border-green-300:hover {
            border-color: rgba(34, 197, 94, 0.4) !important;
        }

        .hover\:border-purple-300:hover {
            border-color: rgba(139, 92, 246, 0.4) !important;
        }

        .hover\:border-blue-300:hover {
            border-color: rgba(59, 130, 246, 0.4) !important;
        }

        .hover\:text-red-200:hover {
            color: #f87171 !important;
        }

        /* Border colors */
        .border-green-200 {
            border-color: rgba(34, 197, 94, 0.3) !important;
        }

        .border-yellow-200 {
            border-color: rgba(234, 179, 8, 0.3) !important;
        }

        .border-green-300 {
            border-color: rgba(34, 197, 94, 0.4) !important;
        }

        .border-yellow-300 {
            border-color: rgba(234, 179, 8, 0.4) !important;
        }

        .border-indigo-300 {
            border-color: rgba(99, 102, 241, 0.4) !important;
        }

        .border-blue-300 {
            border-color: rgba(59, 130, 246, 0.4) !important;
        }

        .border-purple-300 {
            border-color: rgba(139, 92, 246, 0.4) !important;
        }

        .border-green-300 {
            border-color: rgba(34, 197, 94, 0.4) !important;
        }

        /* Mobile responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .search-box input {
                width: 200px;
            }
        }

        @media (max-width: 640px) {
            .search-box input {
                width: 150px;
            }

            .user-info {
                display: none;
            }
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Button styles for dark theme */
        button:hover {
            color: #a5b4fc !important;
        }

        .text-gray-400 {
            color: rgba(226, 232, 240, 0.5) !important;
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Logo -->
        <div class="sidebar-header">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo/sakti.png') }}" alt="SI SAKTI" class="w-12 h-auto">
                <div>
                    <h1 class="text-white font-bold text-xl">SI SAKTI</h1>
                    <p class="text-white/70 text-xs">Sistem Audit Ketataan Instansi</p>
                </div>
            </div>
        </div>

        <!-- Menu -->
        <nav class="sidebar-menu">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>

            <!-- Gudang Aturan -->
            <a href="{{ route('aturan.index') }}" class="menu-item {{ request()->routeIs('aturan*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span>Gudang Aturan</span>
            </a>

            <!-- SKPD -->
            <a href="{{ route('skpd.index') }}" class="menu-item {{ request()->routeIs('skpd*') ? 'active' : '' }}">
                <i class="fas fa-building"></i>
                <span>SKPD</span>
            </a>
            <!-- Upload Data -->
            <a href="#" class="menu-item {{ request()->routeIs('upload*') ? 'active open' : '' }}"
                onclick="toggleSubmenu('upload-submenu')">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Upload Data</span>
                <i class="fas fa-chevron-down chevron"></i>
            </a>
            <div id="upload-submenu" class="submenu {{ request()->routeIs('upload*') ? 'open' : '' }}">
                <a href="{{ route('upload.dpa.index') }}"
                    class="submenu-item {{ request()->routeIs('upload.dpa*') ? 'active' : '' }}">DPA</a>
                <a href="{{ route('upload.anggaran.index') }}"
                    class="submenu-item {{ request()->routeIs('upload.anggaran*') ? 'active' : '' }}">Anggaran
                    Kas</a>
                <a href="{{ route('upload.spj-fungsional.index') }}"
                    class="submenu-item {{ request()->routeIs('upload.spj-fungsional*') ? 'active' : '' }}">SPJ
                    Fungsional (Jan-Des)</a>
                <a href="{{ route('upload.bku.index') }}"
                    class="submenu-item {{ request()->routeIs('upload.bku*') ? 'active' : '' }}">BKU Perbulan
                    (Jan-Des)</a>
                <a href="{{ route('upload.rekening') }}"
                    class="submenu-item {{ request()->routeIs('upload.rekening') ? 'active' : '' }}">Rekening
                    Koran IBB (Jan-Des)</a>
                <a href="{{ route('upload.spj-transaksi.index') }}"
                    class="submenu-item {{ request()->routeIs('upload.spj-transaksi*') ? 'active' : '' }}">SPJ
                    Per Transaksi</a>
            </div>

            <!-- Sinkronisasi Otomatis -->
            <a href="#" class="menu-item {{ request()->routeIs('sinkronisasi*') ? 'active open' : '' }}"
                onclick="toggleSubmenu('sinkronisasi-submenu')">
                <i class="fas fa-sync-alt"></i>
                <span>Sinkron Otomatis</span>
                <i class="fas fa-chevron-down chevron"></i>
            </a>
            <div id="sinkronisasi-submenu" class="submenu {{ request()->routeIs('sinkronisasi*') ? 'open' : '' }}">
                <a href="{{ route('sinkronisasi.rekening-bku') }}"
                    class="submenu-item {{ request()->routeIs('sinkronisasi.rekening-bku') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt mr-2 text-xs"></i>
                    Rekening Koran ↔ BKU Perbulan
                </a>
                <a href="{{ route('sinkronisasi.spj-bku') }}"
                    class="submenu-item {{ request()->routeIs('sinkronisasi.spj-bku') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt mr-2 text-xs"></i>
                    SPJ Fungsional ↔ BKU Perbulan
                </a>
                <a href="{{ route('sinkronisasi.spj-bku2') }}"
                    class="submenu-item {{ request()->routeIs('sinkronisasi.spj-bku2') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt mr-2 text-xs"></i>
                    SPJ ↔ BKU Perbulan
                </a>
                <a href="{{ route('sinkronisasi.saldo') }}"
                    class="submenu-item {{ request()->routeIs('sinkronisasi.saldo') ? 'active' : '' }}">
                    <i class="fas fa-balance-scale mr-2 text-xs"></i>
                    Saldo Bank vs BKU Per Bulan
                </a>
            </div>

            <!-- Analisis Data -->
            <a href="#" class="menu-item {{ request()->routeIs('analisis*') ? 'active open' : '' }}"
                onclick="toggleSubmenu('analisis-submenu')">
                <i class="fas fa-chart-line"></i>
                <span>Analisis Data</span>
                <i class="fas fa-chevron-down chevron"></i>
            </a>
            <div id="analisis-submenu" class="submenu {{ request()->routeIs('analisis*') ? 'open' : '' }}">
                <a href="{{ route('analisis.terperinci') }}"
                    class="submenu-item {{ request()->routeIs('analisis.terperinci') ? 'active' : '' }}">Analisis Data
                    Terperinci</a>
            </div>

            <!-- Laporan -->
            <a href="#" class="menu-item {{ request()->routeIs('laporan*') ? 'active open' : '' }}"
                onclick="toggleSubmenu('laporan-submenu')">
                <i class="fas fa-file-alt"></i>
                <span>Laporan</span>
                <i class="fas fa-chevron-down chevron"></i>
            </a>
            <div id="laporan-submenu" class="submenu {{ request()->routeIs('laporan*') ? 'open' : '' }}">
                <a href="{{ route('laporan.transaksi') }}"
                    class="submenu-item {{ request()->routeIs('laporan.transaksi') ? 'active' : '' }}">
                    <i class="fas fa-table mr-2 text-xs"></i>
                    Laporan Transaksi
                </a>
                <a href="{{ route('laporan.kelengkapan') }}"
                    class="submenu-item {{ request()->routeIs('laporan.kelengkapan') ? 'active' : '' }}">
                    <i class="fas fa-check-circle mr-2 text-xs"></i>
                    Laporan Kelengkapan Dokumen
                </a>
                <a href="{{ route('laporan.transfer') }}"
                    class="submenu-item {{ request()->routeIs('laporan.transfer') ? 'active' : '' }}">
                    <i class="fas fa-university mr-2 text-xs"></i>
                    Laporan Transfer Bank
                </a>
                <a href="{{ route('laporan.distribusi') }}"
                    class="submenu-item {{ request()->routeIs('laporan.distribusi') ? 'active' : '' }}">
                    <i class="fas fa-donate mr-2 text-xs"></i>
                    Laporan Distribusi Dana
                </a>
                <a href="{{ route('laporan.temuan') }}"
                    class="submenu-item {{ request()->routeIs('laporan.temuan') ? 'active' : '' }}">
                    <i class="fas fa-search mr-2 text-xs"></i>
                    Laporan Temuan Audit
                </a>
                <div class="px-6 py-2">
                    <p class="text-white/50 text-xs mb-1">Area Audit:</p>
                    <a href="{{ route('laporan.audit.rkbd') }}"
                        class="submenu-item text-white/60 {{ request()->routeIs('laporan.audit.rkbd') ? 'active' : '' }}">•
                        RKBD</a>
                    <a href="{{ route('laporan.audit.kepegawaian') }}"
                        class="submenu-item text-white/60 {{ request()->routeIs('laporan.audit.kepegawaian') ? 'active' : '' }}">•
                        Kepegawaian</a>
                    <a href="{{ route('laporan.audit.keuangan') }}"
                        class="submenu-item text-white/60 {{ request()->routeIs('laporan.audit.keuangan') ? 'active' : '' }}">•
                        Keuangan</a>
                    <a href="{{ route('laporan.audit.lainnya') }}"
                        class="submenu-item text-white/60 {{ request()->routeIs('laporan.audit.lainnya') ? 'active' : '' }}">•
                        Lainnya</a>
                </div>
                <a href="{{ route('laporan.rekap') }}"
                    class="submenu-item {{ request()->routeIs('laporan.rekap') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice mr-2 text-xs"></i>
                    Laporan Rekap GU vs LS
                </a>
                <a href="{{ route('laporan.rincian') }}"
                    class="submenu-item {{ request()->routeIs('laporan.rincian') ? 'active' : '' }}">
                    <i class="fas fa-list mr-2 text-xs"></i>
                    Laporan Rincian Obyek
                </a>
                <a href="{{ route('laporan.modal') }}"
                    class="submenu-item {{ request()->routeIs('laporan.modal') ? 'active' : '' }}">
                    <i class="fas fa-building mr-2 text-xs"></i>
                    Laporan Belanja Modal
                </a>
            </div>

        </nav>

        <!-- Sidebar Bottom -->
        <div class="sidebar-bottom">
            <div class="sidebar-user-menu">
                <!-- User Profile -->
                <div class="sidebar-user-profile" onclick="toggleUserDropdown()">
                    <div class="sidebar-user-avatar">
                        AD
                    </div>
                    <div class="sidebar-user-info">
                        <h4>Admin</h4>
                        <p>Administrator</p>
                    </div>
                    <i class="fas fa-chevron-up text-gray-400"></i>

                    <!-- User Dropdown -->
                    <div class="user-dropdown" id="userDropdown">
                        <a href="#" class="user-dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Profil</span>
                        </a>
                        <div class="user-dropdown-divider"></div>
                        <a href="#" class="user-dropdown-item danger" onclick="showLogoutModal()">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Keluar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <!-- Left: Menu Toggle & Search -->
            <div class="flex items-center gap-6">
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-600 hover:text-indigo-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Right: Notification -->
            <div class="top-notification-btn">
                <i class="fas fa-bell"></i>
                <span class="top-notification-badge">5</span>
            </div>
        </div>

        @yield('content')
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal-overlay" id="logoutModal">
        <div class="modal">
            <div class="modal-icon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <h3 class="modal-title">Konfirmasi Keluar</h3>
            <p class="modal-text">Apakah Anda yakin ingin keluar dari akun Anda?</p>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" onclick="hideLogoutModal()">
                    Batal
                </button>
                <button class="modal-btn modal-btn-confirm" onclick="confirmLogout()">
                    Keluar
                </button>
            </div>
        </div>
    </div>

    <script>
        // Toggle user dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const profile = document.querySelector('.sidebar-user-profile');
            
            if (!profile.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Show logout modal
        function showLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('show');
        }

        // Hide logout modal
        function hideLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('show');
        }

        // Confirm logout
        function confirmLogout() {
            // Create a form to submit POST request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('logout') }}";
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        }

        // Close modal when clicking overlay
        document.getElementById('logoutModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                hideLogoutModal();
            }
        });
        // Toggle sidebar submenu
        function toggleSubmenu(id) {
            const submenu = document.getElementById(id);
            const menuItem = submenu.previousElementSibling;
            
            submenu.classList.toggle('open');
            menuItem.classList.toggle('open');
        }

        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        // Close sidebar when clicking overlay
        document.querySelector('.sidebar-overlay')?.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    </script>
</body>

</html>