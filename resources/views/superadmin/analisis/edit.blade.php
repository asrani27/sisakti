@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Analisis</h1>
    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('analisis.update', $analisis) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Judul</label>
                <input type="text" name="judul" value="{{ old('judul', $analisis->judul) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    required>
                @error('judul')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">File SPJ</label>
                @if ($analisis->file_spj)
                <div class="mb-2">
                    <a href="{{ Storage::url($analisis->file_spj) }}" target="_blank"
                        class="text-blue-500 hover:text-blue-700 inline-flex items-center gap-2">
                        <i class="fas fa-file"></i> File saat ini
                    </a>
                </div>
                @endif
                <input type="file" name="file_spj"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                <span class="text-gray-500 text-sm">Kosongkan jika tidak ingin mengubah file</span>
                @error('file_spj')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Hasil Analisis</label>
                <textarea name="hasil_analisis" rows="6"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('hasil_analisis', $analisis->hasil_analisis) }}</textarea>
                @error('hasil_analisis')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('analisis.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Batal
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection