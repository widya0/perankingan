<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Helpers\HitungBobotKriteriaHelper;

class DataKriteriaController extends Controller
{
    public function index()
    {
        $bobot = HitungBobotKriteriaHelper::hitungBobotNormal(); // hasil: [id_kriteria => bobot_normal]

        // Ambil nama kriterianya juga biar tampilannya informatif
        $dataKriteria = Kriteria::all();

        return view('datakriteria', compact('bobot', 'dataKriteria'));
    }
}
