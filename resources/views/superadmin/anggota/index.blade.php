@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Management Anggota</h1>
        <a href="{{ route('superadmin.anggota.create') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Tambah Anggota
        </a>
    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($anggota as $index => $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($item->foto)
                        <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->nama }}"
                            class="w-12 h-12 object-cover rounded-full">
                        @else
                        <div class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->nip }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->telp ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($item->user)
                        <span class="text-green-600">
                            <i class="fas fa-check-circle"></i> {{ $item->user->username }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-7 whitespace-nowrap flex gap-2">

                        <a href="{{ route('superadmin.anggota.edit', $item->id) }}"
                            class="text-blue-500 hover:text-blue-700" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if ($item->user_id)
                        <form action="{{ route('superadmin.anggota.reset-password', $item) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit" class="text-yellow-500 hover:text-yellow-700" title="Reset Password"
                                onclick="return confirm('Reset password ke: apipsakti?')">
                                <i class="fas fa-key"></i>
                            </button>
                        </form>
                        @else
                        <form action="{{ route('superadmin.anggota.create-user', $item) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit" class="text-green-500 hover:text-green-700" title="Buat User">
                                <i class="fas fa-user-plus"></i>
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('superadmin.anggota.destroy', $item) }}" method="POST" class="inline"
                            onsubmit="return confirm('Yakin hapus anggota: {{ $item->nama }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data anggota</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection