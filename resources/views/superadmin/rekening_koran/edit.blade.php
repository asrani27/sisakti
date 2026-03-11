@extends('layouts.app')

@section('title', 'Edit Rekening Koran - SI SAKTI')

@section('content')
<div class="content-area">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">Edit Rekening Koran</h1>
        <p class="text-gray-400">Edit data Rekening Koran</p>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="p-4">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-600 mr-2 mt-0.5"></i>
                <div class="text-red-800">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Card -->
    <div class="card-gradient rounded-xl p-8">
        <form action="{{ route('rekening_koran.update', $rekeningKoran->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- SKPD -->
                <div class="input-group transition-all duration-300">
                    <label for="skpd" class="block text-sm font-semibold text-gray-300 mb-2">SKPD</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-building text-gray-400"></i>
                        </div>
                        <select id="skpd" name="kode_skpd" required
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all appearance-none cursor-pointer">
                            <option value="">Pilih SKPD</option>
                            @foreach($skpds as $skpd)
                            <option value="{{ $skpd->kode_skpd }}" {{ old('kode_skpd', $rekeningKoran->kode_skpd) == $skpd->kode_skpd ? 'selected' : '' }}>
                                {{ $skpd->kode_skpd }} - {{ $skpd->nama_skpd }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                    @error('kode_skpd')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bulan -->
                <div class="input-group transition-all duration-300">
                    <label for="bulan" class="block text-sm font-semibold text-gray-300 mb-2">Bulan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-calendar text-gray-400"></i>
                        </div>
                        <select id="bulan" name="bulan" required
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Bulan</option>
                            <option value="Januari" {{ old('bulan', $rekeningKoran->bulan) == 'Januari' ? 'selected' : '' }}>Januari</option>
                            <option value="Februari" {{ old('bulan', $rekeningKoran->bulan) == 'Februari' ? 'selected' : '' }}>Februari</option>
                            <option value="Maret" {{ old('bulan', $rekeningKoran->bulan) == 'Maret' ? 'selected' : '' }}>Maret</option>
                            <option value="April" {{ old('bulan', $rekeningKoran->bulan) == 'April' ? 'selected' : '' }}>April</option>
                            <option value="Mei" {{ old('bulan', $rekeningKoran->bulan) == 'Mei' ? 'selected' : '' }}>Mei</option>
                            <option value="Juni" {{ old('bulan', $rekeningKoran->bulan) == 'Juni' ? 'selected' : '' }}>Juni</option>
                            <option value="Juli" {{ old('bulan', $rekeningKoran->bulan) == 'Juli' ? 'selected' : '' }}>Juli</option>
                            <option value="Agustus" {{ old('bulan', $rekeningKoran->bulan) == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                            <option value="September" {{ old('bulan', $rekeningKoran->bulan) == 'September' ? 'selected' : '' }}>September</option>
                            <option value="Oktober" {{ old('bulan', $rekeningKoran->bulan) == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                            <option value="November" {{ old('bulan', $rekeningKoran->bulan) == 'November' ? 'selected' : '' }}>November</option>
                            <option value="Desember" {{ old('bulan', $rekeningKoran->bulan) == 'Desember' ? 'selected' : '' }}>Desember</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                    @error('bulan')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun -->
                <div class="input-group transition-all duration-300">
                    <label for="tahun" class="block text-sm font-semibold text-gray-300 mb-2">Tahun</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                        </div>
                        <input id="tahun" name="tahun" type="number" required
                            value="{{ old('tahun', $rekeningKoran->tahun) }}"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                            placeholder="Masukkan tahun">
                    </div>
                    @error('tahun')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex items-center gap-4">
                <a href="{{ route('rekening_koran.index') }}"
                    class="px-6 py-3 bg-slate-700 text-gray-300 rounded-xl hover:bg-slate-600 transition-colors font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection