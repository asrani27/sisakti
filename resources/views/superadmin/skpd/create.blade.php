@extends('layouts.app')

@section('title', 'Tambah SKPD - SI SAKTI')

@section('content')
<div class="content-area">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">Tambah SKPD</h1>
        <p class="text-gray-400">Tambah Satuan Kerja Perangkat Daerah baru</p>
    </div>

    <div class="card-gradient rounded-xl p-6">
        <form action="{{ route('skpd.store') }}" method="POST">
            @csrf

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

            <!-- Form Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="kode_skpd" class="block text-sm font-medium text-gray-300 mb-2">
                        Kode SKPD <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="kode_skpd" name="kode_skpd" value="{{ old('kode_skpd') }}"
                        class="w-full px-4 py-2 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none"
                        placeholder="Contoh: 1.01.01.01" required>
                    @error('kode_skpd')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nama_skpd" class="block text-sm font-medium text-gray-300 mb-2">
                        Nama SKPD <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="nama_skpd" name="nama_skpd" value="{{ old('nama_skpd') }}"
                        class="w-full px-4 py-2 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none"
                        placeholder="Contoh: Dinas Pendidikan" required>
                    @error('nama_skpd')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4 mt-6 pt-6 border-t border-slate-700">
                <a href="{{ route('skpd.index') }}"
                    class="px-6 py-2 bg-slate-700 text-gray-200 rounded-lg hover:bg-slate-600 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection