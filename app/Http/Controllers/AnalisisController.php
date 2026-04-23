<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnalisisController extends Controller
{
    public function index()
    {
        $analisis = Analisis::orderBy('created_at', 'desc')->get();
        return view('superadmin.analisis.index', compact('analisis'));
    }

    public function create()
    {
        return view('superadmin.analisis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'file_spj' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:10240',
            'hasil_analisis' => 'nullable',
        ]);

        $data = $request->only(['judul', 'hasil_analisis']);

        if ($request->hasFile('file_spj')) {
            $data['file_spj'] = $request->file('file_spj')->store('analisis', 'public');
        }

        Analisis::create($data);

        return redirect()->route('analisis.index')->with('success', 'Data analisis berhasil ditambahkan');
    }

    public function show(Analisis $analisis)
    {
        return view('superadmin.analisis.show', compact('analisis'));
    }

    public function edit(Analisis $analisis)
    {
        return view('superadmin.analisis.edit', compact('analisis'));
    }

    public function update(Request $request, Analisis $analisis)
    {
        $request->validate([
            'judul' => 'required',
            'file_spj' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:10240',
            'hasil_analisis' => 'nullable',
        ]);

        $data = $request->only(['judul', 'hasil_analisis']);

        if ($request->hasFile('file_spj')) {
            if ($analisis->file_spj) {
                Storage::disk('public')->delete($analisis->file_spj);
            }
            $data['file_spj'] = $request->file('file_spj')->store('analisis', 'public');
        }

        $analisis->update($data);

        return redirect()->route('analisis.index')->with('success', 'Data analisis berhasil diupdate');
    }

    public function destroy(Analisis $analisis)
    {
        if ($analisis->file_spj) {
            Storage::disk('public')->delete($analisis->file_spj);
        }

        $analisis->delete();

        return redirect()->route('analisis.index')->with('success', 'Data analisis berhasil dihapus');
    }
}