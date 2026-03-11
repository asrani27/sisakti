@extends('layouts.app')

@section('title', 'Anggaran Kas - SI SAKTI')

@section('content')
<div class="content-area">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">Anggaran Kas</h1>
        <p class="text-gray-400">Dokumen Pelaksanaan Anggaran Kas</p>
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
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" id="search" placeholder="Cari Anggaran Kas..."
                    class="pl-10 pr-4 py-2 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none w-64">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        <a href="{{ route('upload.anggaran.create') }}"
            class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 transition-all shadow-lg hover:shadow-xl">
            <i class="fas fa-plus"></i>
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
                <tbody>
                    @forelse($angkas as $index => $angka)
                    <tr class="border-b border-slate-800 hover:bg-slate-800/50 transition-colors">
                        <td class="py-3 px-4 text-gray-200">{{ ($angkas->currentPage() - 1) * $angkas->perPage() +
                            $index + 1 }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $angka->skpd ? $angka->skpd->nama_skpd : '-' }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $angka->bulan }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $angka->tahun }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('upload.anggaran.edit', $angka->id) }}"
                                    class="text-blue-400 hover:text-blue-300 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('upload.anggaran.ocr', ['anggaran_id' => $angka->id]) }}"
                                    class="text-emerald-400 hover:text-emerald-300 transition-colors" title="OCR PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <form action="{{ route('upload.anggaran.destroy', $angka->id) }}" method="POST"
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
                            <p>Tidak ada data Anggaran Kas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($angkas->hasPages())
        <div class="mt-6">
            {{ $angkas->links() }}
        </div>
        @endif
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