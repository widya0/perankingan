<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\SubKriteria;

class FKriteriaController extends Controller
{
    public function index()
    {
        $dataKriteria = Kriteria::all();
        $dataSubKriteria = SubKriteria::all();
        return view('fkriteria', compact('dataKriteria'));
    }

    public function TambahKriteria()
    {
        return view('fkriteria', [
            'dataKriteria' => null,
            'dataSubKriteria' => null,
        ]);
    }

    public function SimpanKriteria(Request $request)
    {
        Log::info('Data request sebelum validasi:', $request->all());

          // Ambil semua subkriteria
        $allSubKriteria = $request->input('dataSubKriteria', []);

        // Filter hanya subkriteria yang rentang dan nilai_rentangnya diisi
        $filteredSubKriteria = array_filter($allSubKriteria, function ($item) {
            return !empty($item['rentang']) && isset($item['nilai_rentang']);
        });

        // Update request agar validasinya pakai yang sudah difilter
        $request->merge([
            'dataSubKriteria' => array_values($filteredSubKriteria) // reset index biar 0,1,2
        ]);
        // Validasi input tetap sama
        $validated = $request->validate([
            'nama_kriteria' => 'required|string',
            'bobot_kriteria' => 'required|numeric|max:5', 
            'sifat_kriteria' => 'required|in:Benefit,Cost',
            'dataSubKriteria' => 'required|array|min:2',
            'dataSubKriteria.*.rentang' => 'required|string',
            'dataSubKriteria.*.nilai_rentang' => 'required|numeric',
            
        ]);

        try {
            // DB::beginTransaction(); // Aktifkan transaction

            // Debug input
            Log::info('Request Data:', $request->all());

            // Simpan kriteria
            $dataKriteria = Kriteria::create([
                'nama_kriteria' => $validated['nama_kriteria'],
                'bobot_kriteria' => $validated['bobot_kriteria'],
                'sifat_kriteria' => $validated['sifat_kriteria'],
                
            ]);

            // Debug kriteria yang dibuat
            Log::info('Kriteria created:', $dataKriteria->toArray());

            foreach ($validated['dataSubKriteria'] as $index => $item) {
                Log::info("Processing product item {$index}:", $item);
                
                try {
                    $dataSubKriteria = SubKriteria::create([
                        'id_kriteria' => $dataKriteria->id_kriteria,
                        'rentang' => $item['rentang'],
                        'nilai_rentang' => $item['nilai_rentang'],

                    ]);

                    // Debug sub yang dibuat
                    Log::info("Sub Kriteria berhasil dibuat {$index}:", $dataSubKriteria->toArray());

                } catch (\Exception $e) {
                    Log::error("Error creating transaction for product {$index}: " . $e->getMessage());
                    throw $e;
                }
            }

          
            return redirect()->route('datakriteria')->withInput()->with('success', 'Kriteria berhasil ditambahkan');

        } catch (\Exception $e) {
            // DB::rollback(); // Rollback jika terjadi error
            
            Log::error('Error saat menyimpan kriteria: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function LihatKriteria($id_kriteria)
    {
        $dataKriteria = Kriteria::findOrFail($id_kriteria);

        $dataSubKriteria = SubKriteria::where('id_kriteria', $id_kriteria)
            ->get()
            ->map(function($item) {
                return [
                    'rentang' => $item->rentang ?? '',
                    'nilai_rentang' => $item->nilai_rentang ?? 0,
                ];
            })->toArray();

        return view('fkriterialihat', [
            'dataKriteria' => $dataKriteria,
            'dataSubKriteria' => $dataSubKriteria,
        ]);
    }

    
    public function EditKriteria($id_kriteria = null)
    {
        $dataKriteria = Kriteria::findOrFail($id_kriteria);
        $dataSubKriteria = SubKriteria::where('id_kriteria', $id_kriteria)
            ->get()
            ->map(function($item) {
                return [
                    'rentang' => $item->rentang ?? '',
                    'nilai_rentang' => $item->nilai_rentang ?? 0,
                ];
            })->toArray();

        return view('fkriteria', compact('dataKriteria', 'dataSubKriteria'));
    }

    public function UpdateKriteria(Request $request, $id_kriteria)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string',
            'bobot_kriteria' => 'required|numeric',
            'sifat_kriteria' => 'required|in:Benefit,Cost',
            'dataSubKriteria' => 'required|array|min:2',
            'dataSubKriteria.*.rentang' => 'required|string',
            'dataSubKriteria.*.nilai_rentang' => 'required|numeric',
        ]);

        $kriteria = Kriteria::findOrFail($id_kriteria);
        $kriteria->update([
            'nama_kriteria' => $validated['nama_kriteria'],
            'bobot_kriteria' => $validated['bobot_kriteria'],
            'sifat_kriteria' => $validated['sifat_kriteria'],
        ]);

        // Hapus semua subkriteria lama
        SubKriteria::where('id_kriteria', $id_kriteria)->delete();

        // Simpan ulang subkriteria baru
        foreach ($validated['dataSubKriteria'] as $item) {
            SubKriteria::create([
                'id_kriteria' => $id_kriteria,
                'rentang' => $item['rentang'],
                'nilai_rentang' => $item['nilai_rentang'],
            ]);
        }

        return redirect()->route('datakriteria')->with('success', 'Kriteria berhasil diupdate');
    }

    public function HapusKriteria($id_kriteria)
    {
            // Cegah penghapusan jika ID adalah 1
        if ($id_kriteria == 1) {
            return redirect()->back()
                ->with('error', 'Kriteria ini tidak boleh dihapus.');
        }
        
        try {
            $dataKriteria = Kriteria::findOrFail($id_kriteria);

            SubKriteria::where('id_kriteria', $id_kriteria)->delete();

            $dataKriteria->delete();
            
            return redirect()->route('datakriteria')
                ->with('success', 'Kriteria berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus akun');
        }
    }

}
