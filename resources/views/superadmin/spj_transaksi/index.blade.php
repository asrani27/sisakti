@extends('layouts.app')

@section('title', 'SPJ Per Transaksi - SI SAKTI')

@section('content')
<div class="content-area">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">SPJ Per Transaksi</h1>
        <p class="text-gray-400">Surat Pertanggungjawaban Per Transaksi</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-300 px-4 py-3 rounded-lg mb-4">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-100 border border-red-200 text-red-300 px-4 py-3 rounded-lg mb-4">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    </div>
    @endif

    <!-- Search and Add Button -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-4">
            <div class="search-box">
                <input type="text" id="search" placeholder="Cari SPJ Transaksi..." 
                    class="w-80 px-4 py-2 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none transition-all">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <a href="{{ route('upload.spj-transaksi.create') }}"
            class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Tambah Data
        </a>
    </div>

    <!-- Table Card -->
    <div class="card-gradient rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="text-left py-3 px-4 text-gray-300 font-semibold">No</th>
                        <th class="text-left py-3 px-4 text-gray-300 font-semibold">SKPD</th>
                        <th class="text-left py-3 px-4 text-gray-300 font-semibold">Bulan</th>
                        <th class="text-left py-3 px-4 text-gray-300 font-semibold">Tahun</th>
                        <th class="text-center py-3 px-4 text-gray-300 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @forelse($spjTransaksi as $index => $spj)
                    <tr class="border-b border-slate-800 hover:bg-slate-800/50 transition-colors">
                        <td class="py-3 px-4 text-gray-200">{{ ($spjTransaksi->currentPage() - 1) * $spjTransaksi->perPage() +
                            $index + 1 }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $spj->skpd ? $spj->skpd->nama_skpd : '-' }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $spj->bulan }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $spj->tahun }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('upload.spj-transaksi.edit', $spj->id) }}"
                                    class="text-blue-400 hover:text-blue-300 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('upload.spj-transaksi.ocr', ['spj_transaksi_id' => $spj->id]) }}"
                                    class="text-emerald-400 hover:text-emerald-300 transition-colors" title="OCR PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <form action="{{ route('upload.spj-transaksi.destroy', $spj->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Tidak ada data SPJ Transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($spjTransaksi->hasPages())
        <div class="mt-6">
            {{ $spjTransaksi->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    // Live search functionality
    document.getElementById('search').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let table = document.querySelector('table');
        let rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            let shouldShow = text.includes(searchValue);
            row.style.display = shouldShow ? '' : 'none';
        });

        // Show/hide empty row based on visible rows
        let emptyRow = table.querySelector('tbody tr[colspan]');
        let visibleRows = 0;
        rows.forEach(row => {
            if (row.style.display !== 'none' && row !== emptyRow) {
                visibleRows++;
            }
        });

        if (emptyRow) {
            emptyRow.style.display = visibleRows === 0 ? '' : 'none';
        }
    });
</script>
@endsection