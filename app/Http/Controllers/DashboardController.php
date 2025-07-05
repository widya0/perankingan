<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PenerimaBantuan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $penerimaTampil = PenerimaBantuan::with('alternatif')
            ->where('aksi', 'Tampil')
            ->orderBy('tahun_seleksi', 'desc')
            ->get();

        $tahun = $penerimaTampil->isNotEmpty()
        ? $penerimaTampil->first()->tahun_seleksi
        : date('Y');

        return view('dashboard', compact('penerimaTampil', 'tahun'));
    }
}
