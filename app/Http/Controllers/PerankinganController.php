<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\HasilSAW;
use App\Models\PenerimaBantuan;
use App\Helpers\HitungBobotKriteriaHelper;
use App\Helpers\HitungSAWHelper;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PerankinganController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input('periode', date('Y'));
        $filterDusun = $request->input('filter_dusun');

        $dusunList = Alternatif::getDusunListAktif();

        $bobot = HitungBobotKriteriaHelper::hitungBobotNormal();
        $kriteria = Kriteria::all();
        $rankingRows = HasilSAW::orderBy('ranking')->get();

        $ranking = [];
        foreach ($rankingRows as $row) {
            $ranking[$row->id_alternatif] = $row->nilai_hasil_saw;
        }

        // Ambil alternatif sesuai id hasil ranking
        $ids = array_keys($ranking);

        $query = Alternatif::whereIn('id_alternatif', $ids);
        $alternatif = Alternatif::filterDusun($query, $filterDusun)->get()->keyBy('id_alternatif');


        $detailSAW = HitungSAWHelper::getDetailPerhitungan($periode);

        return view('perankingan', compact('bobot', 'kriteria', 'ranking', 'alternatif', 'periode', 'filterDusun', 'dusunList', 'detailSAW'));
    }

    public function HitungPeringkat(Request $request)
    {
        $periode = $request->input('periode', date('Y'));
        $ranking = HitungSAWHelper::getRanking($periode); // Pastikan ini mengembalikan: [id_alternatif => nilai]

        // Hapus semua hasil sebelumnya
        HasilSAW::simpanRankingBaru($ranking);

        // Simpan hasil ranking baru
        $no = 1;
        foreach ($ranking as $id_alternatif => $nilai) {
            HasilSAW::create([
                'id_hasil_saw' => null, // karena auto increment
                'id_alternatif' => $id_alternatif,
                'nilai_hasil_saw' => $nilai,
                'ranking' => $no++,
            ]);
        }

        return redirect()->route('perankingan')->with('success', 'Perankingan berhasil dihitung.');
    }

    public function AjukanPeringkat()
    {
        $periode = date('Y'); // ambil tahun sekarang otomatis

        $ranking = HitungSAWHelper::getRanking($periode);
        $alternatifData = Alternatif::whereIn('id_alternatif', array_keys($ranking))->get()->keyBy('id_alternatif');

        $topPerDusun = [];
        foreach ($ranking as $id => $nilai) {
            $dusun = $alternatifData[$id]->dusun;
            if (!isset($topPerDusun[$dusun])) {
                $topPerDusun[$dusun] = $id;
                if (count($topPerDusun) == 3) break;
            }
        }

        // Simpan ke tabel penerima_bantuan
        $tahun = date('Y');

        foreach ($topPerDusun as $id_alternatif) {

            // Cek apakah sudah diterima sebelumnya
            if (!PenerimaBantuan::sudahPernahDiterima($id_alternatif, $tahun)) {
                $nilaiSAW = HasilSAW::where('id_alternatif', $id_alternatif)->value('nilai_hasil_saw');

                PenerimaBantuan::create([
                    'id_alternatif' => $id_alternatif,
                    'status' => 'Diajukan',
                    'nilai_hasil' => $nilaiSAW,
                    'tahun_seleksi' => $tahun,
                ]);
            }
        }

        return redirect()->back()->with('success', '3 penerima bantuan telah diajukan.');
    }

    public function PreviewPDFRanking(Request $request)
    {
        return $this->renderPDF($request, 'stream');
    }

    public function DownloadPDFRanking(Request $request)
    {
        return $this->renderPDF($request, 'download');
    }

    protected function renderPDF(Request $request, $mode = 'download')
    {
        $periode = $request->input('periode', date('Y'));
        $filterDusun = $request->input('filter_dusun');

        $ranking = HitungSAWHelper::getRanking($periode);
       

        $query = Alternatif::whereIn('id_alternatif', array_keys($ranking));
        $alternatif = Alternatif::filterDusun($query, $filterDusun)->get()->keyBy('id_alternatif');

        $kriteria = Kriteria::all();

        $data = compact('ranking', 'alternatif', 'kriteria', 'periode', 'filterDusun');
        $pdf = Pdf::loadView('pdfranking', $data)->setPaper('a4', 'landscape');

        $fileName = "hasil-perankingan-{$periode}.pdf";

        return $mode === 'stream' 
            ? $pdf->stream($fileName) 
            : $pdf->download($fileName);
    }


}
