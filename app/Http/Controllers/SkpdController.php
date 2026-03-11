<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use Illuminate\Http\Request;

class SkpdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skpd = Skpd::all();
        return view('superadmin.skpd.index', compact('skpd'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.skpd.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_skpd' => 'required|string|max:255|unique:skpd',
            'nama_skpd' => 'required|string|max:255',
        ]);

        Skpd::create($request->all());

        return redirect()->route('skpd.index')
            ->with('success', 'SKPD berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $skpd = Skpd::findOrFail($id);
        return view('superadmin.skpd.edit', compact('skpd'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_skpd' => 'required|string|max:255|unique:skpd,kode_skpd,' . $id,
            'nama_skpd' => 'required|string|max:255',
        ]);

        $skpd = Skpd::findOrFail($id);
        $skpd->update($request->all());

        return redirect()->route('skpd.index')
            ->with('success', 'SKPD berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $skpd = Skpd::findOrFail($id);
        $skpd->delete();

        return redirect()->route('skpd.index')
            ->with('success', 'SKPD berhasil dihapus');
    }
}