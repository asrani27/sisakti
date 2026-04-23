<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::with('user')->get();
        return view('superadmin.anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('superadmin.anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:anggota,nip',
            'nama' => 'required',
            'telp' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['nip', 'nama', 'telp']);

        // Create user automatically using NIP as username
        $user = User::create([
            'name' => $request->nama,
            'username' => $request->nip,
            'email' => $request->nip . '@sisakti.local',
            'password' => Hash::make('apipsakti'),
            'role' => 'user',
        ]);

        $data['user_id'] = $user->id;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('anggota', 'public');
        }

        Anggota::create($data);

        return redirect()->route('superadmin.anggota.index')->with('success', 'Anggota berhasil ditambahkan');
    }

    public function edit(Anggota $anggota)
    {
        return view('superadmin.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $request->validate([
            'nip' => 'required|unique:anggota,nip,' . $anggota->id,
            'nama' => 'required',
            'telp' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['nip', 'nama', 'telp']);

        if ($request->hasFile('foto')) {
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }
            $data['foto'] = $request->file('foto')->store('anggota', 'public');
        }

        $anggota->update($data);

        // Update user data if exists
        if ($anggota->user) {
            $anggota->user->update([
                'name' => $request->nama,
            ]);
        }

        return redirect()->route('superadmin.anggota.index')->with('success', 'Anggota berhasil diupdate');
    }

    public function createUser($id)
    {
        $anggota = Anggota::findOrFail($id);

        if ($anggota->user_id) {
            return redirect()->back()->with('error', 'User sudah ada untuk anggota ini');
        }

        // Create user using NIP as username
        $user = User::create([
            'name' => $anggota->nama,
            'username' => $anggota->nip,
            'email' => $anggota->nip . '@sisakti.local',
            'password' => Hash::make('apipsakti'),
            'role' => 'user',
        ]);

        $anggota->update(['user_id' => $user->id]);

        return redirect()->back()->with('success', 'User berhasil dibuat dengan password default: apipsakti');
    }

    public function resetPassword($id)
    {
        $anggota = Anggota::findOrFail($id);

        if (!$anggota->user_id) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $anggota->user->update([
            'password' => Hash::make('apipsakti'),
        ]);

        return redirect()->back()->with('success', 'Password berhasil direset ke: apipsakti');
    }

    public function destroy(Anggota $anggota)
    {
        // Delete associated user if exists
        if ($anggota->user_id) {
            User::destroy($anggota->user_id);
        }

        // Delete photo if exists
        if ($anggota->foto) {
            Storage::disk('public')->delete($anggota->foto);
        }

        $anggota->delete();

        return redirect()->route('superadmin.anggota.index')->with('success', 'Anggota berhasil dihapus');
    }
}
