@extends('layouts.app')

@section('title', 'Tambah Aturan - SI SAKTI')

@section('content')
<div class="content-area">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">Tambah Aturan</h1>
        <p class="text-gray-400">Tambahkan peraturan atau regulasi baru ke gudang aturan</p>
    </div>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('aturan.index') }}" 
           class="inline-flex items-center gap-2 text-gray-400 hover:text-indigo-400 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Daftar Aturan
        </a>
    </div>

    <!-- Form Card -->
    <div class="card-gradient rounded-xl p-6">
        <form action="{{ route('aturan.store') }}" method="POST">
            @csrf

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-300 px-4 py-3 rounded-lg mb-4">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="font-semibold">Terjadi kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Fields -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">
                        Kategori <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="kategori" 
                           value="{{ old('kategori') }}"
                           required
                           class="w-full px-4 py-3 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none transition-colors"
                           placeholder="Contoh: Peraturan Pemerintah">
                    @error('kategori')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">
                        Nomor <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="nomor" 
                           value="{{ old('nomor') }}"
                           required
                           class="w-full px-4 py-3 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none transition-colors"
                           placeholder="Contoh: PP No. 45">
                    @error('nomor')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">
                        Tahun <span class="text-red-400">*</span>
                    </label>
                    <input type="number" 
                           name="tahun" 
                           value="{{ old('tahun') }}"
                           required
                           min="1900"
                           max="2100"
                           class="w-full px-4 py-3 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none transition-colors"
                           placeholder="Contoh: 2024">
                    @error('tahun')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-2">
                            File Dokumen <span class="text-red-400">*</span>
                        </label>
                        
                        <!-- File Input -->
                        <div class="relative">
                            <input type="file" 
                                   id="file-upload" 
                                   accept=".pdf"
                                   class="hidden">
                            
                            <!-- Upload Button -->
                            <button type="button" 
                                    onclick="document.getElementById('file-upload').click()"
                                    class="w-full px-4 py-8 bg-slate-800/50 border-2 border-dashed border-slate-600 rounded-lg text-gray-400 hover:border-indigo-500 hover:text-indigo-400 transition-colors flex flex-col items-center gap-2">
                                <i class="fas fa-cloud-upload-alt text-4xl"></i>
                                <span class="text-sm">Klik untuk upload file</span>
                                <span class="text-xs text-gray-500">(Hanya file PDF)</span>
                            </button>
                            
                            <!-- Hidden input to store upload ID -->
                            <input type="hidden" id="upload-id" name="upload_id" value="{{ old('upload_id') }}">
                        </div>
                    
                    <!-- Upload Progress -->
                    <div id="upload-progress-container" class="mt-3 hidden">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-300" id="upload-status">Mengupload...</span>
                            <span class="text-sm text-indigo-400" id="upload-percentage">0%</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-2.5">
                            <div id="upload-progress-bar" class="bg-gradient-to-r from-indigo-600 to-purple-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <!-- Uploaded File Info -->
                    <div id="uploaded-file-info" class="mt-3 hidden">
                        <div class="flex items-center justify-between p-3 bg-slate-800/50 border border-slate-600 rounded-lg">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-file-pdf text-indigo-400 text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-200" id="uploaded-filename">filename.pdf</p>
                                    <p class="text-xs text-gray-500" id="uploaded-filesize">0 KB</p>
                                </div>
                            </div>
                            <button type="button" onclick="resetUpload()" class="text-red-400 hover:text-red-300 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    @error('upload_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Judul -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-200 mb-2">
                        Judul <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="judul" 
                           value="{{ old('judul') }}"
                           required
                           class="w-full px-4 py-3 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none transition-colors"
                           placeholder="Contoh: Peraturan Pemerintah tentang Pengelolaan Keuangan Daerah">
                    @error('judul')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fungsi -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-200 mb-2">
                        Fungsi / Deskripsi
                    </label>
                    <textarea name="fungsi" 
                              rows="4"
                              class="w-full px-4 py-3 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none transition-colors resize-none"
                              placeholder="Jelaskan fungsi atau deskripsi dari aturan ini...">{{ old('fungsi') }}</textarea>
                    @error('fungsi')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-700">
                <a href="{{ route('aturan.index') }}" 
                   class="px-6 py-2.5 border-2 border-slate-600 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-2.5 rounded-lg flex items-center gap-2 transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-save"></i>
                    Simpan Aturan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- TUS Client Library (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/tus-js-client@3.1.0/dist/tus.min.js"></script>

<script>
    let uploadInstance = null;
    const CHUNK_SIZE = 512 * 1024; // 512KB
    
    // File input change handler
    document.getElementById('file-upload').addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // Validate file type - only PDF allowed
        if (file.type !== 'application/pdf') {
            alert('Hanya file PDF yang diperbolehkan!');
            this.value = '';
            return;
        }
        
        // Show filename and size
        document.getElementById('uploaded-filename').textContent = file.name;
        document.getElementById('uploaded-filesize').textContent = formatFileSize(file.size);
        
        // Show progress
        document.getElementById('upload-progress-container').classList.remove('hidden');
        document.getElementById('upload-status').textContent = 'Mengupload...';
        document.getElementById('upload-percentage').textContent = '0%';
        document.getElementById('upload-progress-bar').style.width = '0%';
        
        // Create TUS upload
        const upload = new tus.Upload(file, {
            endpoint: '{{ route('tus.upload') }}',
            chunkSize: CHUNK_SIZE,
            retryDelays: [0, 1000, 3000, 5000],
            metadata: {
                filename: file.name,
                filetype: file.type
            },
            onError: function(error) {
                console.error('Upload failed:', error);
                document.getElementById('upload-status').textContent = 'Upload gagal!';
                document.getElementById('upload-status').classList.add('text-red-400');
                alert('Upload file gagal. Silakan coba lagi.');
            },
            onProgress: function(bytesUploaded, bytesTotal) {
                const percentage = ((bytesUploaded / bytesTotal) * 100).toFixed(2);
                document.getElementById('upload-percentage').textContent = percentage + '%';
                document.getElementById('upload-progress-bar').style.width = percentage + '%';
            },
            onSuccess: function() {
                // Get upload ID from URL
                const uploadUrl = upload.url;
                const uploadId = uploadUrl.split('/').pop();
                
                document.getElementById('upload-id').value = uploadId;
                document.getElementById('upload-status').textContent = 'Upload berhasil!';
                document.getElementById('upload-status').classList.add('text-green-400');
                
                // Show uploaded file info
                setTimeout(() => {
                    document.getElementById('upload-progress-container').classList.add('hidden');
                    document.getElementById('uploaded-file-info').classList.remove('hidden');
                }, 500);
            }
        });
        
        uploadInstance = upload;
        upload.start();
    });
    
    // Reset upload function
    window.resetUpload = function() {
        if (uploadInstance) {
            uploadInstance.abort();
            uploadInstance = null;
        }
        
        document.getElementById('file-upload').value = '';
        document.getElementById('upload-id').value = '';
        document.getElementById('upload-progress-container').classList.add('hidden');
        document.getElementById('uploaded-file-info').classList.add('hidden');
        document.getElementById('upload-status').classList.remove('text-red-400', 'text-green-400');
    };
    
    // Format file size function
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
    
    // Abort upload if page is unloading
    window.addEventListener('beforeunload', function(e) {
        if (uploadInstance && !document.getElementById('uploaded-file-info').classList.contains('hidden') === false) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
</script>
@endsection
