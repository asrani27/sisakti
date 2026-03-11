<?php

namespace App\Http\Controllers;

use App\Models\SpjTransaksi;
use App\Models\Skpd;
use Illuminate\Http\Request;

class SpjTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SpjTransaksi::with('skpd');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('skpd', function ($skpdQuery) use ($search) {
                    $skpdQuery->where('nama_skpd', 'like', "%{$search}%")
                             ->orWhere('kode_skpd', 'like', "%{$search}%");
                })->orWhere('bulan', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%");
            });
        }

        $spjTransaksi = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('superadmin.spj_transaksi.index', compact('spjTransaksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skpds = Skpd::orderBy('nama_skpd')->get();
        return view('superadmin.spj_transaksi.create', compact('skpds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_skpd' => 'required|string',
            'bulan' => 'required|string',
            'tahun' => 'required|string',
        ]);

        // Check for duplicate
        $exists = SpjTransaksi::where('kode_skpd', trim($request->kode_skpd))
                        ->where('bulan', $request->bulan)
                        ->where('tahun', $request->tahun)
                        ->exists();

        if ($exists) {
            return back()->withInput()
                         ->with('error', 'Data SPJ Transaksi untuk SKPD, bulan, dan tahun ini sudah ada!');
        }

        SpjTransaksi::create([
            'kode_skpd' => trim($request->kode_skpd),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);

        return redirect()->route('upload.spj-transaksi.index')
                        ->with('success', 'SPJ Transaksi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $spjTransaksi = SpjTransaksi::findOrFail($id);
        $skpds = Skpd::orderBy('nama_skpd')->get();
        return view('superadmin.spj_transaksi.edit', compact('spjTransaksi', 'skpds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_skpd' => 'required|string',
            'bulan' => 'required|string',
            'tahun' => 'required|string',
        ]);

        $spjTransaksi = SpjTransaksi::findOrFail($id);

        // Check for duplicate (excluding current record)
        $exists = SpjTransaksi::where('kode_skpd', trim($request->kode_skpd))
                        ->where('bulan', $request->bulan)
                        ->where('tahun', $request->tahun)
                        ->where('id', '!=', $id)
                        ->exists();

        if ($exists) {
            return back()->withInput()
                         ->with('error', 'Data SPJ Transaksi untuk SKPD, bulan, dan tahun ini sudah ada!');
        }

        $spjTransaksi->update([
            'kode_skpd' => trim($request->kode_skpd),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);

        return redirect()->route('upload.spj-transaksi.index')
                        ->with('success', 'SPJ Transaksi berhasil diupdate!');
    }

    /**
     * Remove specified resource from storage.
     */
    public function destroy(string $id)
    {
        $spjTransaksi = SpjTransaksi::findOrFail($id);
        $spjTransaksi->delete();

        return redirect()->route('upload.spj-transaksi.index')
                        ->with('success', 'SPJ Transaksi berhasil dihapus!');
    }

    /**
     * Show OCR page for SPJ Transaksi
     */
    public function ocr($spj_transaksi_id)
    {
        return view('superadmin.spj_transaksi.ocr');
    }
}
