<?php

namespace App\Http\Controllers;

use App\Models\PenerimaBantuan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
    [$labels, $dataDisetujui] = PenerimaBantuan::getJumlahPerTahun();
    $dusunSummary = PenerimaBantuan::getSummaryPerDusun();

    return view('beranda', compact('labels', 'dataDisetujui', 'dusunSummary'));
    }
}
