<?php

namespace App\Http\Controllers;

use App\Models\TempatBisnis;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Pastikan Rule di-import jika belum

class TempatBisnisController extends Controller
{
    protected $paginationCount = 10;

    public function index()
    {
        $tempatBisnisList = TempatBisnis::orderBy('nama_tempat')->get(); // Ganti menjadi get()
        return view('tempat_bisnis.index', compact('tempatBisnisList'));
    }

    public function create()
    {
        return view('tempat_bisnis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tempat' => 'required|string|max:255|unique:tempat_bisnis,nama_tempat',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'deskripsi_lokasi' => 'nullable|string', // Tambahkan validasi untuk deskripsi
        ]);
        
        TempatBisnis::create($validated);
        return redirect()
            ->route('tempat-bisnis.index')
            ->with('success', __('Data Tempat Bisnis berhasil ditambahkan.'));
    }

    public function show(TempatBisnis $tempatBisni)
    {
        // Untuk menampilkan deskripsi di halaman show, pastikan view 'tempat_bisnis.show' juga diupdate
        return view('tempat_bisnis.show', ['tempat_bisnis' => $tempatBisni]);
    }

    public function edit(TempatBisnis $tempatBisni)
    {
        return view('tempat_bisnis.edit', ['tempat_bisnis' => $tempatBisni]);
    }

    public function update(Request $request, TempatBisnis $tempatBisni)
    {
        $validated = $request->validate([
            'nama_tempat' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tempat_bisnis')->ignore($tempatBisni->id)
            ],
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'deskripsi_lokasi' => 'nullable|string', // Tambahkan validasi untuk deskripsi
        ]);
        
        $tempatBisni->update($validated);
        return redirect()
            ->route('tempat-bisnis.index')
            ->with('success', __('Data Tempat Bisnis berhasil diperbarui.'));
    }

    public function destroy(TempatBisnis $tempatBisni)
    {
        $tempatBisni->delete();

        return redirect()
            ->route('tempat-bisnis.index')
            ->with('success', __('Data Tempat Bisnis berhasil dihapus.'));
    }
}
