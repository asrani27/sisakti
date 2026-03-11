@extends('layouts.app')

@section('title', 'Dashboard - SI SAKTI')

@section('content')
<!-- Content Area -->
<div class="content-area">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard</h1>
        <p class="text-gray-500">Selamat datang di Sistem Integrasi Sinkronisasi Audit Ketataan Instansi</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Dokumen -->
        <div class="card-gradient rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-500/20 to-transparent rounded-full -translate-y-1/2 translate-x-1/2">
            </div>
            <div class="flex items-center justify-between mb-4 relative">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-file-alt text-white text-xl"></i>
                </div>
                <span
                    class="bg-green-500/20 text-green-400 text-sm font-medium px-3 py-1 rounded-full flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    12%
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-1 relative">1,234</h3>
            <p class="text-gray-400 text-sm relative">Total Dokumen</p>
        </div>

        <!-- Sinkronisasi Aktif -->
        <div class="card-gradient rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/20 to-transparent rounded-full -translate-y-1/2 translate-x-1/2">
            </div>
            <div class="flex items-center justify-between mb-4 relative">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-sync-alt text-white text-xl"></i>
                </div>
                <span
                    class="bg-green-500/20 text-green-400 text-sm font-medium px-3 py-1 rounded-full flex items-center">
                    <i class="fas fa-check mr-1"></i>
                    Aktif
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-1 relative">8/10</h3>
            <p class="text-gray-400 text-sm relative">Sinkronisasi Aktif</p>
        </div>

        <!-- Laporan Terbaru -->
        <div class="card-gradient rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/20 to-transparent rounded-full -translate-y-1/2 translate-x-1/2">
            </div>
            <div class="flex items-center justify-between mb-4 relative">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-pie text-white text-xl"></i>
                </div>
                <span class="bg-blue-500/20 text-blue-400 text-sm font-medium px-3 py-1 rounded-full flex items-center">
                    <i class="fas fa-clock mr-1"></i>
                    Hari ini
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-1 relative">24</h3>
            <p class="text-gray-400 text-sm relative">Laporan Terbaru</p>
        </div>

        <!-- Temuan Audit -->
        <div class="card-gradient rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-500/20 to-transparent rounded-full -translate-y-1/2 translate-x-1/2">
            </div>
            <div class="flex items-center justify-between mb-4 relative">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
                <span
                    class="bg-orange-500/20 text-orange-400 text-sm font-medium px-3 py-1 rounded-full flex items-center">
                    <i class="fas fa-arrow-down mr-1"></i>
                    8%
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-1 relative">7</h3>
            <p class="text-gray-400 text-sm relative">Temuan Audit</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Upload Data Cepat -->
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-cloud-upload-alt text-indigo-600 mr-2"></i>
                Upload Data
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="#"
                    class="p-4 bg-gray-50 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 border-2 border-transparent transition-all group">
                    <div
                        class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mb-2 group-hover:bg-indigo-200">
                        <i class="fas fa-file-invoice text-indigo-600"></i>
                    </div>
                    <p class="font-medium text-gray-700">DPA</p>
                </a>
                <a href="#"
                    class="p-4 bg-gray-50 rounded-xl hover:bg-green-50 hover:border-green-300 border-2 border-transparent transition-all group">
                    <div
                        class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-2 group-hover:bg-green-200">
                        <i class="fas fa-money-bill-wave text-green-600"></i>
                    </div>
                    <p class="font-medium text-gray-700">Anggaran Kas</p>
                </a>
                <a href="#"
                    class="p-4 bg-gray-50 rounded-xl hover:bg-purple-50 hover:border-purple-300 border-2 border-transparent transition-all group">
                    <div
                        class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-2 group-hover:bg-purple-200">
                        <i class="fas fa-sync text-purple-600"></i>
                    </div>
                    <p class="font-medium text-gray-700">BKU Bulanan</p>
                </a>
                <a href="#"
                    class="p-4 bg-gray-50 rounded-xl hover:bg-blue-50 hover:border-blue-300 border-2 border-transparent transition-all group">
                    <div
                        class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-2 group-hover:bg-blue-200">
                        <i class="fas fa-university text-blue-600"></i>
                    </div>
                    <p class="font-medium text-gray-700">Rekening Koran</p>
                </a>
            </div>
        </div>

        <!-- Sinkronisasi Otomatis -->
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-sync-alt text-purple-600 mr-2"></i>
                Sinkronisasi Otomatis
            </h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl border border-green-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Rekening Koran ↔ BKU</p>
                            <p class="text-xs text-gray-500">Terakhir sinkron: 5 menit lalu</p>
                        </div>
                    </div>
                    <span class="text-green-600 text-sm font-medium">Aktif</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl border border-green-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">SPJ ↔ BKU</p>
                            <p class="text-xs text-gray-500">Terakhir sinkron: 10 menit lalu</p>
                        </div>
                    </div>
                    <span class="text-green-600 text-sm font-medium">Aktif</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Saldo Bank vs BKU</p>
                            <p class="text-xs text-gray-500">Menunggu data...</p>
                        </div>
                    </div>
                    <span class="text-yellow-600 text-sm font-medium">Pending</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-history text-indigo-600 mr-2"></i>
            Aktivitas Terbaru
        </h2>
        <div class="space-y-4">
            <div class="flex items-start p-4 bg-gray-50 rounded-xl">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                    <i class="fas fa-upload text-blue-600"></i>
                </div>
                <div class="flex-grow">
                    <p class="font-medium text-gray-800">Upload BKU Bulan Januari berhasil</p>
                    <p class="text-sm text-gray-500">Admin • 2 jam yang lalu</p>
                </div>
            </div>
            <div class="flex items-start p-4 bg-gray-50 rounded-xl">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                    <i class="fas fa-sync text-green-600"></i>
                </div>
                <div class="flex-grow">
                    <p class="font-medium text-gray-800">Sinkronisasi Rekening Koran dengan BKU selesai</p>
                    <p class="text-sm text-gray-500">Sistem • 3 jam yang lalu</p>
                </div>
            </div>
            <div class="flex items-start p-4 bg-gray-50 rounded-xl">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                    <i class="fas fa-file-alt text-purple-600"></i>
                </div>
                <div class="flex-grow">
                    <p class="font-medium text-gray-800">Laporan Temuan Audit baru dibuat</p>
                    <p class="text-sm text-gray-500">Admin • 5 jam yang lalu</p>
                </div>
            </div>
            <div class="flex items-start p-4 bg-gray-50 rounded-xl">
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                    <i class="fas fa-exclamation text-orange-600"></i>
                </div>
                <div class="flex-grow">
                    <p class="font-medium text-gray-800">Temuan baru pada SPJ Transaksi #1234</p>
                    <p class="text-sm text-gray-500">Sistem • 6 jam yang lalu</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection