<?php

namespace App\Http\Controllers;

use App\Models\Aturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aturan = Aturan::orderBy('created_at', 'desc')->get();
        return view('superadmin.aturan.index', compact('aturan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.aturan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'nomor' => 'required|string|max:255',
            'tahun' => 'required|integer',
            'upload_id' => 'required|string', // Made required
            'fungsi' => 'nullable|string',
        ]);

        // Handle file upload from TUS
        $filePath = null;
        if ($request->filled('upload_id')) {
            $uploadData = Cache::get("tus_complete_{$request->upload_id}");
            
            if ($uploadData) {
                // Validate file extension is PDF
                $extension = strtolower(pathinfo($uploadData['filename'], PATHINFO_EXTENSION));
                if ($extension !== 'pdf') {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Hanya file PDF yang diperbolehkan!');
                }
                
                $filePath = $uploadData['path'];
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'File upload tidak ditemukan atau belum selesai.');
            }
        }

        // Create aturan record
        $aturan = Aturan::create([
            'kategori' => $validated['kategori'],
            'judul' => $validated['judul'],
            'nomor' => $validated['nomor'],
            'tahun' => $validated['tahun'],
            'file' => $filePath,
            'fungsi' => $validated['fungsi'],
        ]);

        // Clear the cache after using the upload data
        if ($request->filled('upload_id')) {
            Cache::forget("tus_complete_{$request->upload_id}");
        }

        return redirect()->route('aturan.index')
            ->with('success', 'Aturan berhasil ditambahkan.');
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
        $aturan = Aturan::findOrFail($id);
        return view('superadmin.aturan.edit', compact('aturan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'nomor' => 'required|string|max:255',
            'tahun' => 'required|integer',
            'upload_id' => 'nullable|string',
            'fungsi' => 'nullable|string',
        ]);

        $aturan = Aturan::findOrFail($id);

        // Handle file upload from TUS
        $filePath = $aturan->file; // Keep existing file by default
        if ($request->filled('upload_id')) {
            $uploadData = Cache::get("tus_complete_{$request->upload_id}");
            
            if ($uploadData) {
                // Validate file extension is PDF
                $extension = strtolower(pathinfo($uploadData['filename'], PATHINFO_EXTENSION));
                if ($extension !== 'pdf') {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Hanya file PDF yang diperbolehkan!');
                }
                
                // Delete old file from S3 if exists
                if ($aturan->file) {
                    Storage::disk('s3')->delete($aturan->file);
                }
                
                $filePath = $uploadData['path'];
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'File upload tidak ditemukan atau belum selesai.');
            }
        }

        // Update aturan record
        $aturan->update([
            'kategori' => $validated['kategori'],
            'judul' => $validated['judul'],
            'nomor' => $validated['nomor'],
            'tahun' => $validated['tahun'],
            'file' => $filePath,
            'fungsi' => $validated['fungsi'],
        ]);

        // Clear the cache after using the upload data
        if ($request->filled('upload_id')) {
            Cache::forget("tus_complete_{$request->upload_id}");
        }

        return redirect()->route('aturan.index')
            ->with('success', 'Aturan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aturan = Aturan::findOrFail($id);

        // Delete file from S3 if exists
        if ($aturan->file) {
            Storage::disk('s3')->delete($aturan->file);
        }

        $aturan->delete();

        return redirect()->route('aturan.index')
            ->with('success', 'Aturan berhasil dihapus.');
    }
}
