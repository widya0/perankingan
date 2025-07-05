<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alternatif;

class DataCalonController extends Controller
{
    public function index(Request $request)
    {
       // Ambil semua ID Alternatif yang statusnya Aktif
       

        // Ambil filter dusun dari request
        $filterDusun = $request->input('filter_dusun');

        $dusunList = Alternatif::getDusunListAktif();

        // Ambil semua alternatif yang tidak pernah 'Disetujui'
        $query = Alternatif::whereDoesntHave('penerimaBantuan', function ($q) {
            $q->where('status_penerima', 'Disetujui');
        });

        $query = Alternatif::filterDusun($query, $filterDusun);

        $dataCalon = $query->orderBy('dusun', 'desc')->get();
        return view('datacalon', compact('dataCalon', 'filterDusun', 'dusunList'));
    }

    // CalonController.php
    public function verifikasi($id)
    {
        if (auth()->user()->level !== 'Admin') {
            abort(403, 'Anda tidak memiliki izin untuk melakukan verifikasi data.');
        }

        $datacalon = Alternatif::findOrFail($id);

        // Toggle enum
        $datacalon->verifikasi = $datacalon->verifikasi === 'Terverifikasi' ? 'Belum' : 'Terverifikasi';
        $datacalon->save();

        return back()->with('success', 'Status verifikasi berhasil diubah.');
    }

}
