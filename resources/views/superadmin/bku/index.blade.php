@extends('layouts.app')

@section('title', 'BKU - SI SAKTI')

@section('content')
<div class="content-area">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">BKU Perbulan</h1>
        <p class="text-gray-400">Buku Kas Umum Perbulan</p>
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

    <!-- Actions -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" id="search" placeholder="Cari BKU..."
                    class="pl-10 pr-4 py-2 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none w-64">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        <a href="{{ route('upload.bku.create') }}"
            class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 transition-all shadow-lg hover:shadow-xl">
            <i class="fas fa-plus"></i>
            Tambah Data
        </a>
    </div>

    <!-- Table Card -->
    <div class="card-gradient rounded-xl overflow-hidden">
        <!-- Table -->
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
                    @forelse($bkus as $index => $bku)
                    <tr class="border-b border-slate-800 hover:bg-slate-800/50 transition-colors">
                        <td class="py-3 px-4 text-gray-200">{{ ($bkus->currentPage() - 1) * $bkus->perPage() + $index +
                            1 }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $bku->skpd ? $bku->skpd->nama_skpd : '-' }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $bku->bulan }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $bku->tahun }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="showBkuDetails({{ $bku->id }})"
                                    class="text-indigo-400 hover:text-indigo-300 transition-colors"
                                    title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('upload.bku.edit', $bku->id) }}"
                                    class="text-blue-400 hover:text-blue-300 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('upload.bku.ocr', ['bku_id' => $bku->id]) }}"
                                    class="text-emerald-400 hover:text-emerald-300 transition-colors" title="OCR PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <form action="{{ route('upload.bku.destroy', $bku->id) }}" method="POST"
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
                            <p>Tidak ada data BKU</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bkus->hasPages())
        <div class="mt-6">
            {{ $bkus->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal BKU Details -->
<div id="bkuDetailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-[9999]">
    <div class="bg-slate-900 rounded-xl shadow-2xl w-full max-w-6xl mx-4 max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-700">
            <div>
                <h2 class="text-2xl font-bold text-white">Detail BKU</h2>
                <p id="bkuModalInfo" class="text-gray-400 mt-1"></p>
            </div>
            <button onclick="closeBkuModal()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-150px)]">
            <div id="bkuDetailsContent">
                <!-- Content will be loaded here -->
            </div>
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

    function showBkuDetails(bkuId) {
    const modal = document.getElementById('bkuDetailModal');
    const content = document.getElementById('bkuDetailsContent');
    const info = document.getElementById('bkuModalInfo');
    
    content.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500"></div>
            <span class="ml-3 text-gray-400">Memuat data...</span>
        </div>
    `;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    fetch(`/superadmin/upload/bku/${bkuId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                info.textContent = `${data.bku.skpd} - ${data.bku.bulan} ${data.bku.tahun}`;
                
                if (data.details.length === 0) {
                    content.innerHTML = `
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-4xl text-gray-500 mb-4"></i>
                            <p class="text-gray-400">Belum ada data transaksi</p>
                        </div>
                    `;
                } else {
                    let html = `
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-slate-700 bg-slate-800/50">
                                        <th class="text-left py-3 px-4 text-gray-300 font-semibold">No</th>
                                        <th class="text-left py-3 px-4 text-gray-300 font-semibold">Tanggal</th>
                                        <th class="text-left py-3 px-4 text-gray-300 font-semibold">Uraian</th>
                                        <th class="text-right py-3 px-4 text-gray-300 font-semibold">Penerimaan</th>
                                        <th class="text-right py-3 px-4 text-gray-300 font-semibold">Pengeluaran</th>
                                        <th class="text-right py-3 px-4 text-gray-300 font-semibold">Saldo</th>
                                        <th class="text-center py-3 px-4 text-gray-300 font-semibold">Status OCR</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    
                    data.details.forEach((detail, index) => {
                        html += `
                            <tr class="border-b border-slate-700 hover:bg-slate-800/30 transition-colors">
                                <td class="py-3 px-4 text-gray-200">${index + 1}</td>
                                <td class="py-3 px-4 text-gray-200">${detail.tanggal ? formatDate(detail.tanggal) : '-'}</td>
                                <td class="py-3 px-4 text-gray-200">${detail.uraian || '-'}</td>
                                <td class="py-3 px-4 text-gray-200 text-right">${detail.penerimaan ? formatNumber(detail.penerimaan) : '-'}</td>
                                <td class="py-3 px-4 text-gray-200 text-right">${detail.pengeluaran ? formatNumber(detail.pengeluaran) : '-'}</td>
                                <td class="py-3 px-4 text-gray-200 text-right">${detail.saldo ? formatNumber(detail.saldo) : '-'}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs ${detail.status_ocr === 'Jelas' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400'}">
                                        <i class="fas fa-circle text-[6px] mr-2"></i>
                                        ${detail.status_ocr || '-'}
                                    </span>
                                </td>
                            </tr>
                        `;
                    });
                    
                    html += `
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 p-4 bg-slate-800/50 rounded-lg">
                            <p class="text-gray-400">Total Transaksi: <span class="text-white font-semibold">${data.details.length}</span></p>
                        </div>
                    `;
                    
                    content.innerHTML = html;
                }
            } else {
                content.innerHTML = `
                    <div class="text-center py-12">
                        <i class="fas fa-exclamation-circle text-4xl text-red-500 mb-4"></i>
                        <p class="text-gray-400">${data.message || 'Gagal memuat data'}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="text-center py-12">
                    <i class="fas fa-exclamation-circle text-4xl text-red-500 mb-4"></i>
                    <p class="text-gray-400">Terjadi kesalahan saat memuat data</p>
                </div>
            `;
        });
}

function closeBkuModal() {
    const modal = document.getElementById('bkuDetailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric' 
    };
    return new Intl.DateTimeFormat('id-ID', options).format(date);
}

// Close modal when clicking outside
document.getElementById('bkuDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBkuModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBkuModal();
    }
});
</script>
@endsection