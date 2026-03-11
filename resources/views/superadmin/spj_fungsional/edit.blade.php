@extends('layouts.app')

@section('title', 'Edit SPJ Fungsional - SI SAKTI')

@section('content')
<div class="content-area">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">Edit SPJ Fungsional</h1>
        <p class="text-gray-400">Edit data Surat Pertanggungjawaban Fungsional</p>
    </div>

    <div class="card-gradient rounded-xl p-6">
        <form action="{{ route('upload.spj-fungsional.update', $spjFungsional->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Alert Messages -->
            @if(session('error'))
            <div class="bg-red-100 border border-red-200 text-red-300 px-4 py-3 rounded-lg mb-4 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
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

            <!-- Form Fields -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="mb-4">
                    <label for="kode_skpd" class="block text-sm font-medium text-gray-300 mb-2">
                        SKPD <span class="text-red-400">*</span>
                    </label>
                    <select id="kode_skpd" name="kode_skpd"
                        class="w-full px-4 py-2 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none" required>
                        <option value="">Pilih SKPD</option>
                        @foreach($skpds as $skpd)
                        <option value="{{ trim($skpd->kode_skpd) }}" {{ old('kode_skpd', trim($spjFungsional->kode_skpd)) == trim($skpd->kode_skpd) ? 'selected' : '' }}>
                            {{ trim($skpd->kode_skpd) }} - {{ $skpd->nama_skpd }}
                        </option>
                        @endforeach
                    </select>
                    @error('kode_skpd')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="bulan" class="block text-sm font-medium text-gray-300 mb-2">
                        Bulan <span class="text-red-400">*</span>
                    </label>
                    <select id="bulan" name="bulan"
                        class="w-full px-4 py-2 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none" required>
                        <option value="">Pilih Bulan</option>
                        <option value="Januari" {{ old('bulan', $spjFungsional->bulan) == 'Januari' ? 'selected' : '' }}>Januari</option>
                        <option value="Februari" {{ old('bulan', $spjFungsional->bulan) == 'Februari' ? 'selected' : '' }}>Februari</option>
                        <option value="Maret" {{ old('bulan', $spjFungsional->bulan) == 'Maret' ? 'selected' : '' }}>Maret</option>
                        <option value="April" {{ old('bulan', $spjFungsional->bulan) == 'April' ? 'selected' : '' }}>April</option>
                        <option value="Mei" {{ old('bulan', $spjFungsional->bulan) == 'Mei' ? 'selected' : '' }}>Mei</option>
                        <option value="Juni" {{ old('bulan', $spjFungsional->bulan) == 'Juni' ? 'selected' : '' }}>Juni</option>
                        <option value="Juli" {{ old('bulan', $spjFungsional->bulan) == 'Juli' ? 'selected' : '' }}>Juli</option>
                        <option value="Agustus" {{ old('bulan', $spjFungsional->bulan) == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                        <option value="September" {{ old('bulan', $spjFungsional->bulan) == 'September' ? 'selected' : '' }}>September</option>
                        <option value="Oktober" {{ old('bulan', $spjFungsional->bulan) == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                        <option value="November" {{ old('bulan', $spjFungsional->bulan) == 'November' ? 'selected' : '' }}>November</option>
                        <option value="Desember" {{ old('bulan', $spjFungsional->bulan) == 'Desember' ? 'selected' : '' }}>Desember</option>
                    </select>
                    @error('bulan')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tahun" class="block text-sm font-medium text-gray-300 mb-2">
                        Tahun <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="tahun" name="tahun" value="{{ old('tahun', $spjFungsional->tahun) }}"
                        class="w-full px-4 py-2 bg-slate-800/50 border-2 border-slate-700 rounded-lg text-gray-200 focus:border-indigo-500 focus:outline-none"
                        placeholder="Contoh: 2026" required>
                    @error('tahun')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4 mt-6 pt-6 border-t border-slate-700">
                <a href="{{ route('upload.spj-fungsional.index') }}"
                    class="px-6 py-2 bg-slate-700 text-gray-200 rounded-lg hover:bg-slate-600 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection