<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use App\Models\PenerimaBantuan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PenerimaController extends Controller
{
    public function index(Request $request)
    {
        $filterDusun = $request->input('filter_dusun');
        $filterTahun = $request->input('filter_tahun');
        $filterStatus = $request->input('filter_status');

        $ids = PenerimaBantuan::pluck('id_alternatif')->toArray();
        $query = Alternatif::whereIn('id_alternatif', $ids);
        $filteredAlternatif = Alternatif::filterDusun($query, $filterDusun)->get();

        $penerima = PenerimaBantuan::with('alternatif')
            ->whereIn('id_alternatif', $filteredAlternatif->pluck('id_alternatif'))
            ->when($filterTahun, function ($query) use ($filterTahun) {
                $query->where('tahun_seleksi', $filterTahun);
            })
            ->when($filterStatus, function ($query) use ($filterStatus) {
                $query->where('status_penerima', $filterStatus);
            })
            ->orderBy('tahun_seleksi', 'desc')
            ->get();


        $tahunList = PenerimaBantuan::select('tahun_seleksi')
            ->distinct()
            ->orderBy('tahun_seleksi', 'desc')
            ->pluck('tahun_seleksi');

        $statusList = PenerimaBantuan::select('status_penerima')
            ->distinct()
            ->orderBy('status_penerima', 'desc')
            ->pluck('status_penerima');

        $dusunList = Alternatif::getDusunListAktif();

        return view('penerima', compact('penerima', 'filterDusun', 'filterTahun', 'filterStatus', 'dusunList', 'tahunList', 'statusList'));
    }

    public function EditStatusPenerima(Request $request, $id)
    {
        $request->validate([
            'status_penerima' => 'required|in:Diajukan,Diterima',
        ]);

        $penerima = PenerimaBantuan::findOrFail($id);
        $penerima->status_penerima = $request->status_penerima;
        $penerima->save();

        return redirect()->back()->with('success', 'Status penerima berhasil diperbarui.');
    }

    public function UpdateStatusPenerima(Request $request, $id)
    {
        $penerima = PenerimaBantuan::findOrFail($id);
        $penerima->status_penerima = $request->input('status_penerima');
        $penerima->save();

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function KirimDashboard(Request $request)
    {
        $tahun = $request->input('tahun');
        $aksi = $request->input('aksi');

        PenerimaBantuan::TampilDashboard($tahun, $aksi);

        return redirect()->back()->with('success', 'Data berhasil diperbarui ke dashboard.');
    }

    public function PreviewPDFPenerima(Request $request)
    {
        return $this->renderPDFPenerima($request, 'stream');
    }

    public function DownloadPDFPenerima(Request $request)
    {
        return $this->renderPDFPenerima($request, 'download');
    }

    protected function renderPDFPenerima(Request $request, $mode = 'download')
    {
        $filterDusun = $request->input('filter_dusun');
        $filterTahun = $request->input('filter_tahun');
        $filterStatus = $request->input('filter_status');

        $ids = PenerimaBantuan::pluck('id_alternatif')->toArray();
        $query = Alternatif::whereIn('id_alternatif', $ids);
        $filteredAlternatif = Alternatif::filterDusun($query, $filterDusun)->get();

        $penerima = PenerimaBantuan::with('alternatif')
            ->whereIn('id_alternatif', $filteredAlternatif->pluck('id_alternatif'))
            ->when($filterTahun, function ($query) use ($filterTahun) {
                $query->where('tahun_seleksi', $filterTahun);
            })
            ->when($filterStatus, function ($query) use ($filterStatus) {
                $query->where('status_penerima', $filterStatus);
            })
            ->orderBy('status_penerima', 'desc')
            ->get();

        $data = compact('penerima', 'filterDusun', 'filterTahun', 'filterStatus');

        $pdf = Pdf::loadView('pdfpenerima', $data)->setPaper('a4', 'landscape');

        $fileName = "data-penerima-{$filterTahun}.pdf";

        return $mode === 'stream'
            ? $pdf->stream($fileName)
            : $pdf->download($fileName);
    }

}
