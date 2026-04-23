@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Detail Analisis</h1>
        <a href="{{ route('analisis.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-4">
            <label class="block text-gray-500 text-sm">Judul</label>
            <p class="text-lg font-semibold">{{ $analisis->judul }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-500 text-sm">File SPJ</label>
            @if ($analisis->file_spj)
            <a href="{{ Storage::url($analisis->file_spj) }}" target="_blank"
                class="text-blue-500 hover:text-blue-700 inline-flex items-center gap-2">
                <i class="fas fa-file"></i> Download File
            </a>
            @else
            <span class="text-gray-400">Tidak ada file</span>
            @endif
        </div>

        <div class="mb-4">
            <label class="block text-gray-500 text-sm">Hasil Analisis</label>
            <div class="bg-gray-50 p-4 rounded-lg mt-2">
                {!! nl2br(e($analisis->hasil_analisis ?? '-')) !!}
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-500 text-sm">Tanggal Dibuat</label>
            <p>{{ $analisis->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('analisis.edit', $analisis) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                Edit
            </a>
        </div>
    </div>
</div>
@endsection