<?php

namespace App\Http\Controllers;

use App\Models\RekeningKoran;
use App\Models\Skpd;
use Illuminate\Http\Request;

class RekeningKoranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekeningKorans = RekeningKoran::with('skpd')->latest()->paginate(10);
        return view('superadmin.rekening_koran.index', compact('rekeningKorans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skpds = Skpd::all();
        return view('superadmin.rekening_koran.create', compact('skpds'));
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

        // Check if Rekening Koran with the same kode_skpd, bulan, and tahun already exists
        $existingRekeningKoran = RekeningKoran::where('kode_skpd', $validated['kode_skpd'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->first();

        if ($existingRekeningKoran) {
            return redirect()
                ->route('rekening_koran.create')
                ->with('error', 'Data Rekening Koran untuk SKPD, bulan, dan tahun tersebut sudah ada.')
                ->withInput();
        }

        RekeningKoran::create($validated);

        return redirect()->route('rekening_koran.index')
            ->with('success', 'Data Rekening Koran berhasil ditambahkan.');
    }

    /**
     * Show the specified resource.
     */
    public function show(string $id)
    {
        $rekeningKoran = RekeningKoran::with('skpd')->findOrFail($id);
        return view('superadmin.rekening_koran.show', compact('rekeningKoran'));
    }

    /**
     * Get Rekening Koran details as JSON for modal display.
     */
    public function getDetails($id)
    {
        $rekeningKoran = RekeningKoran::with('skpd')->find($id);
        
        if (!$rekeningKoran) {
            return response()->json([
                'success' => false,
                'message' => 'Rekening Koran tidak ditemukan'
            ], 404);
        }
        
        // Rekening Koran doesn't have a separate details table like BKU
        // Return empty details array for now
        return response()->json([
            'success' => true,
            'rekeningKoran' => [
                'id' => $rekeningKoran->id,
                'skpd' => $rekeningKoran->skpd ? $rekeningKoran->skpd->nama_skpd : '-',
                'bulan' => $rekeningKoran->bulan,
                'tahun' => $rekeningKoran->tahun
            ],
            'details' => []
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rekeningKoran = RekeningKoran::findOrFail($id);
        $skpds = Skpd::all();
        return view('superadmin.rekening_koran.edit', compact('rekeningKoran', 'skpds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rekeningKoran = RekeningKoran::findOrFail($id);

        // Trim and clean the kode_skpd input
        $request->merge([
            'kode_skpd' => trim($request->input('kode_skpd')),
        ]);

        $validated = $request->validate([
            'kode_skpd' => 'required|exists:skpd,kode_skpd',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        // Check if Rekening Koran with the same kode_skpd, bulan, and tahun already exists (excluding current)
        $existingRekeningKoran = RekeningKoran::where('kode_skpd', $validated['kode_skpd'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->where('id', '!=', $id)
            ->first();

        if ($existingRekeningKoran) {
            return redirect()
                ->route('rekening_koran.edit', $id)
                ->with('error', 'Data Rekening Koran untuk SKPD, bulan, dan tahun tersebut sudah ada.')
                ->withInput();
        }

        $rekeningKoran->update($validated);

        return redirect()->route('rekening_koran.index')
            ->with('success', 'Data Rekening Koran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rekeningKoran = RekeningKoran::findOrFail($id);
        $rekeningKoran->delete();

        return redirect()->route('rekening_koran.index')
            ->with('success', 'Data Rekening Koran berhasil dihapus.');
    }

    /**
     * Show OCR page for Rekening Koran
     */
    public function ocr($rekening_koran_id)
    {
        return view('superadmin.rekening_koran.ocr');
    }
}
