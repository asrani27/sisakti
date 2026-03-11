@extends('layouts.app')

@section('title', 'Edit Aturan - SI SAKTI')

@section('content')
<!-- TUS Upload Script from CDN -->
<script src="https://cdn.jsdelivr.net/npm/tus-js-client@3.1.0/dist/tus.min.js"></script>

<div class="content-area">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">Edit Aturan</h1>
        <p class="text-gray-400">Edit informasi aturan yang sudah ada</p>
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
        <form action="{{ route('aturan.update', $aturan->id) }}" method="POST">
            @csrf
            @method('PUT')

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
                           value="{{ old('kategori', $aturan->kategori) }}"
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
                           value="{{ old('nomor', $aturan->nomor) }}"
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
                           value="{{ old('tahun', $aturan->tahun) }}"
                           required
                           min="1900"
                           max="2100"
                           class="w-full px-4 py-3 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none transition-colors"
                           placeholder="Contoh: 2024">
                    @error('tahun')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload (Optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">
                        File Dokumen (PDF Only)
                    </label>
                    
                    <!-- Current File Display -->
                    @if($aturan->file)
                    <div class="mb-3 p-3 bg-slate-800/30 border border-slate-700 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-file-pdf text-red-400 text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-200 font-medium">{{ basename($aturan->file) }}</p>
                                    <p class="text-xs text-gray-500">File saat ini</p>
                                </div>
                            </div>
                            <a href="{{ Storage::disk('s3')->url($aturan->file) }}" 
                               target="_blank"
                               class="text-indigo-400 hover:text-indigo-300 text-sm">
                                <i class="fas fa-external-link-alt"></i> Lihat
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Upload Area -->
                    <div class="border-2 border-dashed border-slate-600 rounded-lg p-6 hover:border-indigo-500 transition-colors cursor-pointer" 
                         id="dropzone-edit"
                         onclick="document.getElementById('file-upload-edit').click()">
                        <input type="file" 
                               id="file-upload-edit" 
                               accept=".pdf" 
                               class="hidden"
                               onchange="handleFileSelectEdit(event)">
                        
                        <div id="upload-prompt-edit">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <i class="fas fa-cloud-upload-alt text-4xl text-slate-500"></i>
                                <p class="text-gray-400 text-center">
                                    <span class="text-indigo-400 font-medium">Klik untuk upload</span> 
                                    atau drag & drop file PDF
                                </p>
                                <p class="text-xs text-slate-500">Maksimal 10MB</p>
                            </div>
                        </div>

                        <!-- Upload Progress -->
                        <div id="upload-progress-edit" class="hidden">
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span id="upload-filename-edit" class="text-gray-200 truncate"></span>
                                        <span id="upload-percentage-edit" class="text-indigo-400">0%</span>
                                    </div>
                                    <div class="w-full bg-slate-700 rounded-full h-2">
                                        <div id="progress-bar-edit" 
                                             class="bg-gradient-to-r from-indigo-600 to-purple-600 h-2 rounded-full transition-all duration-300" 
                                             style="width: 0%"></div>
                                    </div>
                                    <p id="upload-status-edit" class="text-xs text-gray-500 mt-2">Mengupload...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Complete -->
                        <div id="upload-complete-edit" class="hidden">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                                <div>
                                    <p class="text-green-400 font-medium">Upload Berhasil!</p>
                                    <p id="uploaded-filename-edit" class="text-sm text-gray-400 truncate"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Error -->
                        <div id="upload-error-edit" class="hidden">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-exclamation-circle text-red-400 text-2xl"></i>
                                <div>
                                    <p class="text-red-400 font-medium">Upload Gagal</p>
                                    <p id="upload-error-message-edit" class="text-sm text-gray-400 truncate"></p>
                                    <button type="button" 
                                            onclick="resetUploadEdit()" 
                                            class="mt-2 text-xs text-indigo-400 hover:text-indigo-300">
                                        Coba lagi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden input for upload ID -->
                    <input type="hidden" 
                           id="upload-id-edit" 
                           name="upload_id" 
                           value="{{ old('upload_id') }}">

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
                           value="{{ old('judul', $aturan->judul) }}"
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
                              placeholder="Jelaskan fungsi atau deskripsi dari aturan ini...">{{ old('fungsi', $aturan->fungsi) }}</textarea>
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
                    Perbarui Aturan
                </button>
            </div>
        </form>
    </div>

    <script>
        let uploadEdit = null;

        // Handle file selection
        function handleFileSelectEdit(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validate file type
            if (file.type !== 'application/pdf') {
                alert('Hanya file PDF yang diperbolehkan!');
                return;
            }

            // Validate file size (max 10MB)
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes
            if (file.size > maxSize) {
                alert('Ukuran file maksimal 10MB!');
                return;
            }

            // Start TUS upload
            uploadFileTUSEdit(file);
        }

        // Upload file using TUS protocol
        function uploadFileTUSEdit(file) {
            // Show progress UI
            document.getElementById('upload-prompt-edit').classList.add('hidden');
            document.getElementById('upload-progress-edit').classList.remove('hidden');
            document.getElementById('upload-filename-edit').textContent = file.name;

            // Create TUS upload
            const options = {
                endpoint: '{{ route('tus.upload') }}',
                chunkSize: 512 * 1024, // 512KB
                metadata: {
                    filename: file.name,
                    filetype: file.type
                },
                onError: function(error) {
                    console.error('Upload failed:', error);
                    showErrorEdit(error.message || 'Gagal mengupload file');
                },
                onProgress: function(bytesUploaded, bytesTotal) {
                    const percentage = Math.round((bytesUploaded / bytesTotal) * 100);
                    document.getElementById('upload-percentage-edit').textContent = percentage + '%';
                    document.getElementById('progress-bar-edit').style.width = percentage + '%';
                    document.getElementById('upload-status-edit').textContent = 
                        `Mengupload... ${formatBytes(bytesUploaded)} / ${formatBytes(bytesTotal)}`;
                },
                onSuccess: function() {
                    console.log('Upload complete!');
                    
                    // Get upload ID from URL
                    const uploadId = uploadEdit.url.split('/').pop();
                    
                    // Store upload ID in hidden input
                    document.getElementById('upload-id-edit').value = uploadId;
                    
                    // Show success UI
                    showCompleteEdit(document.getElementById('upload-filename-edit').textContent);
                }
            };

            uploadEdit = new tus.Upload(file, options);
            uploadEdit.start();
        }

        // Show upload complete
        function showCompleteEdit(filename) {
            document.getElementById('upload-progress-edit').classList.add('hidden');
            document.getElementById('upload-complete-edit').classList.remove('hidden');
            document.getElementById('uploaded-filename-edit').textContent = filename;
        }

        // Show upload error
        function showErrorEdit(message) {
            document.getElementById('upload-progress-edit').classList.add('hidden');
            document.getElementById('upload-error-edit').classList.remove('hidden');
            document.getElementById('upload-error-message-edit').textContent = message;
        }

        // Reset upload
        function resetUploadEdit() {
            document.getElementById('upload-error-edit').classList.add('hidden');
            document.getElementById('upload-complete-edit').classList.add('hidden');
            document.getElementById('upload-prompt-edit').classList.remove('hidden');
            document.getElementById('upload-id-edit').value = '';
            document.getElementById('progress-bar-edit').style.width = '0%';
            document.getElementById('upload-percentage-edit').textContent = '0%';
            document.getElementById('file-upload-edit').value = '';
        }

        // Format bytes to human readable
        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Handle drag and drop
        const dropzoneEdit = document.getElementById('dropzone-edit');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzoneEdit.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzoneEdit.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzoneEdit.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropzoneEdit.classList.add('border-indigo-500', 'bg-indigo-500/10');
        }

        function unhighlight(e) {
            dropzoneEdit.classList.remove('border-indigo-500', 'bg-indigo-500/10');
        }

        dropzoneEdit.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                document.getElementById('file-upload-edit').files = files;
                handleFileSelectEdit({ target: { files: files } });
            }
        }
    </script>
</div>
@endsection
