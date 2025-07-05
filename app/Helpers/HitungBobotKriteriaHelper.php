<?php

namespace App\Helpers;
use App\Models\Kriteria;

class HitungBobotKriteriaHelper
{
     public static function hitungBobotNormal()
    {
        $kriteria = Kriteria::all();
        $total = $kriteria->sum('bobot_kriteria');

        $normal = [];
        foreach ($kriteria as $k) {
            $normal[$k->id_kriteria] = $total > 0 ? $k->bobot_kriteria / $total : 0;
        }

        return $normal;
    }
}
