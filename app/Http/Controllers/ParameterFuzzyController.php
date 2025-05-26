<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use App\Models\HimpunanFuzzy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ParameterFuzzyController extends Controller
{
    public function index()
    {
        $parameters = Parameter::with('himpunanFuzzies')->orderBy('nama_parameter')->get();
        return view('parameters.index', compact('parameters'));
    }

    public function create()
    {
        return view('parameters.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_parameter' => 'required|string|unique:parameters,nama_parameter|max:255',
            'himpunan' => 'required|array|min:1',
            'himpunan.*.nama_himpunan' => 'required|string|max:100',
            'himpunan.*.nilai_linguistik_view' => 'required|string|max:100',
            'himpunan.*.nilai_crisp_input' => 'required|numeric',
            'himpunan.*.mf_a' => 'required|numeric',
            'himpunan.*.mf_b' => 'required|numeric',
            'himpunan.*.mf_c' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $parameter = Parameter::create(['nama_parameter' => $request->nama_parameter]);

            foreach ($request->himpunan as $himpunanData) {
                $himpunanData['parameter_id'] = $parameter->id;
                HimpunanFuzzy::create($himpunanData);
            }

            DB::commit();
            return redirect()->route('parameters.index')->with('success', 'Parameter berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan parameter: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Parameter $parameter)
    {
        $parameter->load('himpunanFuzzies');
        return view('parameters.edit', compact('parameter'));
    }

    public function update(Request $request, Parameter $parameter)
    {
         $request->validate([
            'nama_parameter' => ['required', 'string', 'max:255', Rule::unique('parameters')->ignore($parameter->id)],
            'himpunan' => 'required|array|min:1',
            'himpunan.*.nama_himpunan' => 'required|string|max:100',
            'himpunan.*.nilai_linguistik_view' => 'required|string|max:100',
            'himpunan.*.nilai_crisp_input' => 'required|numeric',
            'himpunan.*.mf_a' => 'required|numeric',
            'himpunan.*.mf_b' => 'required|numeric',
            'himpunan.*.mf_c' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $parameter->update(['nama_parameter' => $request->nama_parameter]);

            // Hapus himpunan lama
            $parameter->himpunanFuzzies()->delete();

            // Tambahkan himpunan baru
            foreach ($request->himpunan as $himpunanData) {
                 $himpunanData['parameter_id'] = $parameter->id;
                 HimpunanFuzzy::create($himpunanData);
            }

            DB::commit();
            return redirect()->route('parameters.index')->with('success', 'Parameter berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui parameter: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Parameter $parameter)
    {
        try {
            $parameter->delete(); // Himpunan fuzzy akan terhapus otomatis karena cascade onDelete
            return redirect()->route('parameters.index')->with('success', 'Parameter berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('parameters.index')->with('error', 'Gagal menghapus parameter: ' . $e->getMessage());
        }
    }
}