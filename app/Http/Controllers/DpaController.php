<?php

namespace App\Http\Controllers;

use App\Models\Dpa;
use App\Models\Skpd;
use Illuminate\Http\Request;

class DpaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dpas = Dpa::with('skpd')->latest()->paginate(10);
        return view('superadmin.dpa.index', compact('dpas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skpds = Skpd::all();
        return view('superadmin.dpa.create', compact('skpds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Trim and clean the kode_skpd input
        $request->merge([
            'kode_skpd' => trim($request->input('kode_skpd')),
        ]);

        $validated = $request->validate([
            'kode_skpd' => 'required|exists:skpd,kode_skpd',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        // Check if DPA with the same kode_skpd, bulan, and tahun already exists
        $existingDpa = Dpa::where('kode_skpd', $validated['kode_skpd'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->first();

        if ($existingDpa) {
            return redirect()
                ->route('upload.dpa.create')
                ->with('error', 'Data DPA untuk SKPD, bulan, dan tahun tersebut sudah ada.')
                ->withInput();
        }

        Dpa::create($validated);

        return redirect()->route('upload.dpa.index')
            ->with('success', 'Data DPA berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dpa = Dpa::with('skpd')->findOrFail($id);
        return view('superadmin.dpa.show', compact('dpa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dpa = Dpa::findOrFail($id);
        $skpds = Skpd::all();
        return view('superadmin.dpa.edit', compact('dpa', 'skpds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dpa = Dpa::findOrFail($id);

        // Trim and clean the kode_skpd input
        $request->merge([
            'kode_skpd' => trim($request->input('kode_skpd')),
        ]);

        $validated = $request->validate([
            'kode_skpd' => 'required|exists:skpd,kode_skpd',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        // Check if DPA with the same kode_skpd, bulan, and tahun already exists (excluding current DPA)
        $existingDpa = Dpa::where('kode_skpd', $validated['kode_skpd'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->where('id', '!=', $id)
            ->first();

        if ($existingDpa) {
            return redirect()
                ->route('upload.dpa.edit', $id)
                ->with('error', 'Data DPA untuk SKPD, bulan, dan tahun tersebut sudah ada.')
                ->withInput();
        }

        $dpa->update($validated);

        return redirect()->route('upload.dpa.index')
            ->with('success', 'Data DPA berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dpa = Dpa::findOrFail($id);
        $dpa->delete();

        return redirect()->route('upload.dpa.index')
            ->with('success', 'Data DPA berhasil dihapus.');
    }

    /**
     * Show OCR page for DPA
     */
    public function ocr($dpa_id)
    {
        return view('superadmin.dpa.ocr');
    }
}
