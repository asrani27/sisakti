<?php

namespace App\Http\Controllers;

use App\Models\SpjFungsional;
use App\Models\Skpd;
use Illuminate\Http\Request;

class SpjFungsionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SpjFungsional::with('skpd');

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

        $spjFungsional = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('superadmin.spj_fungsional.index', compact('spjFungsional'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skpds = Skpd::orderBy('nama_skpd')->get();
        return view('superadmin.spj_fungsional.create', compact('skpds'));
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
        $exists = SpjFungsional::where('kode_skpd', trim($request->kode_skpd))
                        ->where('bulan', $request->bulan)
                        ->where('tahun', $request->tahun)
                        ->exists();

        if ($exists) {
            return back()->withInput()
                         ->with('error', 'Data SPJ Fungsional untuk SKPD, bulan, dan tahun ini sudah ada!');
        }

        SpjFungsional::create([
            'kode_skpd' => trim($request->kode_skpd),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);

        return redirect()->route('upload.spj-fungsional')
                        ->with('success', 'SPJ Fungsional berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $spjFungsional = SpjFungsional::findOrFail($id);
        $skpds = Skpd::orderBy('nama_skpd')->get();
        return view('superadmin.spj_fungsional.edit', compact('spjFungsional', 'skpds'));
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

        $spjFungsional = SpjFungsional::findOrFail($id);

        // Check for duplicate (excluding current record)
        $exists = SpjFungsional::where('kode_skpd', trim($request->kode_skpd))
                        ->where('bulan', $request->bulan)
                        ->where('tahun', $request->tahun)
                        ->where('id', '!=', $id)
                        ->exists();

        if ($exists) {
            return back()->withInput()
                         ->with('error', 'Data SPJ Fungsional untuk SKPD, bulan, dan tahun ini sudah ada!');
        }

        $spjFungsional->update([
            'kode_skpd' => trim($request->kode_skpd),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);

        return redirect()->route('upload.spj-fungsional')
                        ->with('success', 'SPJ Fungsional berhasil diupdate!');
    }

    /**
     * Remove specified resource from storage.
     */
    public function destroy(string $id)
    {
        $spjFungsional = SpjFungsional::findOrFail($id);
        $spjFungsional->delete();

        return redirect()->route('upload.spj-fungsional')
                        ->with('success', 'SPJ Fungsional berhasil dihapus!');
    }

    /**
     * Show OCR page for SPJ Fungsional
     */
    public function ocr($spj_fungsional_id)
    {
        return view('superadmin.spj_fungsional.ocr');
    }
}
