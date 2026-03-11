@extends('layouts.app')

@section('title', 'OCR PDF - BKU - SI SAKTI')

@section('content')
<div class="content-area">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">OCR PDF BKU</h1>
        <p class="text-gray-400">Upload file PDF untuk melakukan scan dan ekstraksi data</p>
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

    <!-- Warning Message -->
    @if(session('warning'))
    <div class="bg-yellow-100 border border-yellow-200 text-yellow-300 px-4 py-3 rounded-lg mb-4">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('warning') }}
        </div>
    </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-100 border border-red-200 text-red-300 px-4 py-3 rounded-lg mb-4">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card-gradient rounded-xl p-6">
        <!-- Upload Form -->
        <form action="{{ route('upload.bku.ocr.process') }}" method="POST" enctype="multipart/form-data" id="ocrForm">
            @csrf

            <!-- BKU Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    BKU Terpilih
                </label>
                <div class="w-full px-4 py-3 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-lg">{{ $selectedBku->skpd ? $selectedBku->skpd->nama_skpd : 'SKPD Tidak Ditemukan' }}</p>
                            <p class="text-sm text-gray-400 mt-1">{{ $selectedBku->bulan }} {{ $selectedBku->tahun }}</p>
                        </div>
                        <input type="hidden" name="bku_id" value="{{ $selectedBku->id }}">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400">
                            <i class="fas fa-check-circle mr-2"></i>
                            Terpilih
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label for="pdf_file" class="block text-sm font-medium text-gray-300 mb-2">
                    Upload File PDF <span class="text-red-400">*</span>
                </label>
                <div
                    class="border-2 border-dashed border-slate-700 rounded-lg p-8 text-center hover:border-indigo-500 transition-colors">
                    <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" class="hidden" required
                        onchange="updateFileName()">
                    <label for="pdf_file" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-500 mb-4"></i>
                        <p class="text-gray-300 mb-2">Klik untuk memilih file PDF</p>
                        <p class="text-gray-500 text-sm">atau drag & drop file di sini</p>
                        <p id="fileName" class="text-indigo-400 mt-4 font-medium hidden"></p>
                    </label>
                </div>
                @error('pdf_file')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Progress Bar -->
            <div id="progressContainer" class="mb-6 hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-300">Memproses file...</span>
                    <span id="progressPercent" class="text-sm text-indigo-400">0%</span>
                </div>
                <div class="w-full bg-slate-700 rounded-full h-2">
                    <div id="progressBar"
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 h-2 rounded-full transition-all duration-300"
                        style="width: 0%"></div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-700">
                <a href="{{ route('upload.bku.index') }}"
                    class="px-6 py-2 bg-slate-700 text-gray-200 rounded-lg hover:bg-slate-600 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg">
                    <i class="fas fa-file-import mr-2"></i>
                    Scan & Ekstrak Data
                </button>
            </div>
        </form>
    </div>

    <!-- Extracted Data Table -->
    @if(session('extractedData') && !empty(session('extractedData')))
    @php $extractedData = session('extractedData'); @endphp
    <div class="card-gradient rounded-xl p-6 mt-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">
                <i class="fas fa-table mr-2 text-emerald-400"></i>
                Data yang Diekstrak
            </h2>
            <div class="flex items-center gap-3">
                <form action="{{ route('upload.bku.ocr.save') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="extractedData" value='@json(session('extractedData'))'>
                    <input type="hidden" name="metadata" value='@json(session('metadata'))'>
                    <input type="hidden" name="bku_id" value='{{ session('bku_id') }}'>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Data
                    </button>
                </form>
                <button onclick="exportToCSV()"
                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all shadow-lg">
                    <i class="fas fa-download mr-2"></i>
                    Export CSV
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="extractedTable" class="w-full">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="text-left py-3 px-4 text-gray-300 font-semibold">No</th>
                        <th class="text-left py-3 px-4 text-gray-300 font-semibold">Tanggal</th>
                        <th class="text-left py-3 px-4 text-gray-300 font-semibold">No Bukti</th>
                        <th class="text-left py-3 px-4 text-gray-300 font-semibold">Uraian</th>
                        <th class="text-right py-3 px-4 text-gray-300 font-semibold">Penerimaan</th>
                        <th class="text-right py-3 px-4 text-gray-300 font-semibold">Pengeluaran</th>
                        <th class="text-right py-3 px-4 text-gray-300 font-semibold">Saldo</th>
                        <th class="text-center py-3 px-4 text-gray-300 font-semibold">Status OCR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($extractedData as $index => $row)
                    <tr class="border-b border-slate-800 hover:bg-slate-800/50 transition-colors">
                        <td class="py-3 px-4 text-gray-200">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $row['tanggal'] ?? '-' }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $row['nomor_bukti'] ?? '-' }}</td>
                        <td class="py-3 px-4 text-gray-200">{{ $row['uraian'] ?? '-' }}</td>
                        <td class="py-3 px-4 text-gray-200 text-right">{{ isset($row['penerimaan']) ? number_format($row['penerimaan'], 0, ',', '.') : '-' }}</td>
                        <td class="py-3 px-4 text-gray-200 text-right">{{ isset($row['pengeluaran']) ? number_format($row['pengeluaran'], 0, ',', '.') : '-' }}</td>
                        <td class="py-3 px-4 text-gray-200 text-right">{{ isset($row['saldo']) ? number_format($row['saldo'], 0, ',', '.') : '-' }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-500/20 text-green-400">
                                <i class="fas fa-check-circle mr-2"></i>
                                Jelas
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<script>
    function updateFileName() {
    const input = document.getElementById('pdf_file');
    const fileName = document.getElementById('fileName');
    
    if (input.files && input.files[0]) {
        fileName.textContent = input.files[0].name;
        fileName.classList.remove('hidden');
    } else {
        fileName.classList.add('hidden');
    }
}

// Drag and drop functionality
const dropZone = document.querySelector('.border-dashed');
const fileInput = document.getElementById('pdf_file');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.classList.add('border-indigo-500', 'bg-slate-800/50');
    }, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.classList.remove('border-indigo-500', 'bg-slate-800/50');
    }, false);
});

dropZone.addEventListener('drop', (e) => {
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        updateFileName();
    }
});

// Show progress during form submission
document.getElementById('ocrForm').addEventListener('submit', function(e) {
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    
    progressContainer.classList.remove('hidden');
    
    // Simulate progress for UX
    let progress = 0;
    const interval = setInterval(() => {
        if (progress < 90) {
            progress += 5;
            progressBar.style.width = progress + '%';
            progressPercent.textContent = progress + '%';
        } else {
            clearInterval(interval);
        }
    }, 200);
});

function exportToCSV() {
    const table = document.getElementById('extractedTable');
    const rows = table.querySelectorAll('tr');
    
    let csv = [];
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        let rowData = [];
        cols.forEach(col => {
            rowData.push(col.textContent.trim());
        });
        csv.push(rowData.join(','));
    });
    
    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = 'bku_ekstrak.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>
@endsection