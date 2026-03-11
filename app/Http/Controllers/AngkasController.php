<?php

namespace App\Http\Controllers;

use App\Models\Angkas;
use App\Models\Skpd;
use Illuminate\Http\Request;

class AngkasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Angkas::with('skpd');

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

        $angkas = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('superadmin.angkas.index', compact('angkas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skpds = Skpd::orderBy('nama_skpd')->get();
        return view('superadmin.angkas.create', compact('skpds'));
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
        $exists = Angkas::where('kode_skpd', trim($request->kode_skpd))
                        ->where('bulan', $request->bulan)
                        ->where('tahun', $request->tahun)
                        ->exists();

        if ($exists) {
            return back()->withInput()
                         ->with('error', 'Data Anggaran Kas untuk SKPD, bulan, dan tahun ini sudah ada!');
        }

        Angkas::create([
            'kode_skpd' => trim($request->kode_skpd),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);

        return redirect()->route('upload.anggaran.index')
                        ->with('success', 'Anggaran Kas berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $angkas = Angkas::findOrFail($id);
        $skpds = Skpd::orderBy('nama_skpd')->get();
        return view('superadmin.angkas.edit', compact('angkas', 'skpds'));
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

        $angkas = Angkas::findOrFail($id);

        // Check for duplicate (excluding current record)
        $exists = Angkas::where('kode_skpd', trim($request->kode_skpd))
                        ->where('bulan', $request->bulan)
                        ->where('tahun', $request->tahun)
                        ->where('id', '!=', $id)
                        ->exists();

        if ($exists) {
            return back()->withInput()
                         ->with('error', 'Data Anggaran Kas untuk SKPD, bulan, dan tahun ini sudah ada!');
        }

        $angkas->update([
            'kode_skpd' => trim($request->kode_skpd),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);

        return redirect()->route('upload.anggaran.index')
                        ->with('success', 'Anggaran Kas berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $angkas = Angkas::findOrFail($id);
        $angkas->delete();

        return redirect()->route('upload.anggaran.index')
                        ->with('success', 'Anggaran Kas berhasil dihapus!');
    }

    /**
     * Show OCR page for Anggaran Kas
     */
    public function ocr($anggaran_id)
    {
        return view('superadmin.angkas.ocr');
    }
}
