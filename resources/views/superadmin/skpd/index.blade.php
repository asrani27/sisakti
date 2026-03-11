@extends('layouts.app')

@section('title', 'SKPD - SI SAKTI')

@section('content')
<div class="content-area">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">SKPD</h1>
        <p class="text-gray-400">Kelola Satuan Kerja Perangkat Daerah</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-600 px-4 py-3 rounded-lg mb-4 flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-200 text-red-300 px-4 py-3 rounded-lg mb-4 flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" id="search" placeholder="Cari SKPD..."
                    class="pl-10 pr-4 py-2 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none w-64">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        <a href="{{ route('skpd.create') }}"
            class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 transition-all shadow-lg hover:shadow-xl">
            <i class="fas fa-plus"></i>
            Tambah SKPD
        </a>
    </div>

    <!-- Table Card -->
    <div class="card-gradient rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-800/50 border-b border-slate-700">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-200">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-200">Kode SKPD</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-200">Nama SKPD</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skpd as $item)
                    <tr class="border-b border-slate-700/50 hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-300">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-indigo-100 text-indigo-600 font-mono">
                                {{ $item->kode_skpd }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-200">{{ $item->nama_skpd }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('skpd.edit', $item->id) }}"
                                    class="w-8 h-8 rounded-lg bg-blue-100 text-blue-500 hover:bg-blue-200 hover:text-blue-600 flex items-center justify-center transition-colors"
                                    title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('skpd.destroy', $item->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus SKPD ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-red-100 text-red-300 hover:bg-red-200 hover:text-red-400 flex items-center justify-center transition-colors"
                                        title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-building text-4xl mb-3 opacity-50"></i>
                            <p class="text-lg font-medium">Belum ada SKPD</p>
                            <p class="text-sm">Silakan tambahkan SKPD baru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Search functionality
    document.getElementById('search').addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const table = document.querySelector('table tbody');
        const rows = table.querySelectorAll('tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection