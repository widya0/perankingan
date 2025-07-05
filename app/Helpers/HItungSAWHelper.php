<?php

namespace App\Helpers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\PenerimaBantuan;
use App\Helpers\HitungBobotKriteriaHelper;

class HitungSAWHelper
{
    public static function matriksKeputusan($periode)
    {
        $daftarSudahDisetujui = PenerimaBantuan::where('status_penerima', 'Diterima')
            ->pluck('id_alternatif')
            ->toArray();

        $penilaian = Penilaian::with('subKriteria')
            ->where('periode', $periode)
            ->whereNotIn('id_alternatif', $daftarSudahDisetujui)
            ->get();

        $matriks = [];

        foreach ($penilaian as $p) {
            $matriks[$p->id_alternatif][$p->id_kriteria] = $p->subKriteria->nilai_rentang;
        }

        return $matriks;
    }

    public static function matriksKeputusanNormalisasi($periode)
    {
        $matriks = self::matriksKeputusan($periode);
        $kriteria = Kriteria::all();
        $matriksNormal = [];

        foreach ($kriteria as $k) {
            $idKriteria = $k->id_kriteria;
            $nilaiSemuaAlternatif = [];

            foreach ($matriks as $idAlternatif => $nilaiKriteria) {
                if (isset($nilaiKriteria[$idKriteria])) {
                    $nilaiSemuaAlternatif[$idAlternatif] = $nilaiKriteria[$idKriteria];
                }
            }

            if (empty($nilaiSemuaAlternatif)) continue;

            $max = max($nilaiSemuaAlternatif);
            $min = min($nilaiSemuaAlternatif);

            foreach ($nilaiSemuaAlternatif as $idAlternatif => $nilai) {
                if ($k->sifat_kriteria == 'Benefit') {
                    $matriksNormal[$idAlternatif][$idKriteria] = $max != 0 ? $nilai / $max : 0;
                } else {
                    $matriksNormal[$idAlternatif][$idKriteria] = $nilai != 0 ? $min / $nilai : 0;
                }
            }
        }

        return $matriksNormal;
    }

    public static function hitungPreferensi($periode)
    {
        $bobot = HitungBobotKriteriaHelper::hitungBobotNormal();
        $matriksNormal = self::matriksKeputusanNormalisasi($periode);
        $preferensi = [];

        foreach ($matriksNormal as $idAlternatif => $nilaiKriteria) {
            $total = 0;
            foreach ($nilaiKriteria as $idKriteria => $nilai) {
                $total += $nilai * ($bobot[$idKriteria] ?? 0);
            }
            $preferensi[$idAlternatif] = $total;
        }

        return $preferensi;
    }

    public static function getRanking($periode)
    {
        $gabung = self::gabungkanPreferensiDenganKerusakan($periode);
        $gabung = self::tambahkanDataDusun($gabung);
        $topPerDusun = self::ambilTigaTerbaikPerDusun($gabung);
        $preferensi = self::hitungPreferensi($periode);

        return self::susunFinalRanking($topPerDusun, $preferensi);
    }

    private static function gabungkanPreferensiDenganKerusakan($periode)
    {
        $KERUSAKAN_KRITERIA_ID = 1;
        $preferensi = self::hitungPreferensi($periode);
        $normalisasi = self::matriksKeputusanNormalisasi($periode);

        $gabung = [];

        foreach ($preferensi as $id => $nilai) {
            $gabung[] = [
                'id_alternatif' => $id,
                'preferensi' => $nilai,
                'nilai_kerusakan' => $normalisasi[$id][$KERUSAKAN_KRITERIA_ID] ?? 0,
            ];
        }

        return $gabung;
    }

    private static function tambahkanDataDusun($gabung)
    {
        $alternatifData = Alternatif::whereIn('id_alternatif', array_column($gabung, 'id_alternatif'))
            ->get()
            ->keyBy('id_alternatif');

        foreach ($gabung as &$row) {
            $row['dusun'] = $alternatifData[$row['id_alternatif']]->dusun ?? null;
        }

        return $gabung;
    }

    private static function ambilTigaTerbaikPerDusun($gabung)
    {
        $kelompokDusun = collect($gabung)->groupBy('dusun');
        $top = [];

        foreach ($kelompokDusun as $dusun => $items) {
            $max = collect($items)->max('preferensi');
            $kandidat = collect($items)->filter(fn($i) => $i['preferensi'] == $max);

            $teratas = $kandidat->count() === 1
                ? $kandidat->first()
                : $kandidat->sortByDesc('nilai_kerusakan')->first();

            if ($teratas) {
                $top[] = [
                    'id_alternatif' => $teratas['id_alternatif'],
                    'nilai' => $teratas['preferensi'],
                ];
            }

            if (count($top) >= 3) break;
        }

        return collect($top)->sortByDesc('nilai')->values();
    }

    private static function susunFinalRanking($topPerDusun, $preferensi)
    {
        $ranking = [];
        $sudah = [];

        foreach ($topPerDusun as $data) {
            $ranking[$data['id_alternatif']] = $data['nilai'];
            $sudah[] = $data['id_alternatif'];
        }

        $sisa = collect($preferensi)
            ->filter(fn($nilai, $id) => !in_array($id, $sudah))
            ->sortDesc();

        foreach ($sisa as $id => $nilai) {
            $ranking[$id] = $nilai;
        }

        return $ranking;
    }


    public static function getDetailPerhitungan($periode)
    {
        $matriks = self::matriksKeputusan($periode);
        $normalisasi = self::matriksKeputusanNormalisasi($periode);
        $preferensi = self::hitungPreferensi($periode);

        return [
            'matriks_keputusan'    => $matriks,
            'matriks_normalisasi'  => $normalisasi,
            'preferensi'           => $preferensi
        ];
    }
}
