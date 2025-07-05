<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Alternatif;
use App\Models\Penilaian;
use App\Models\Kriteria;
use App\Models\SubKriteria;

class FCalonController extends Controller
{
    // Tampilkan daftar calon
    public function index()
    {
        $dataCalon = Alternatif::all();
        $dataKriteria = Kriteria::with('subKriteria')->get();
        $dataPenilaian = Alternatif::all();
        return view('fcalon', compact('dataCalon','dataKriteria', 'dataPenilaian'));
    }

    // Form tambah
    public function TambahCalon(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama' => 'required|string',
            'nik' => 'required|string',
            'dusun' => 'required|in:Bendungan,Made,Ngampel',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'tahun_masuk' => 'required|string',
            'ktp' => 'nullable|image|max:2048',
            'rumah' => 'nullable|image|max:2048',
            'kk' => 'nullable|image|max:2048',
            // 'id_pengguna' => 'required|numeric',
            'kriteria' => 'required|array'
        ]);

        // 2. Upload file (jika ada)
        $ktpPath = $request->file('ktp')?->store('dokumen/ktp', 'public');
        $rumahPath = $request->file('rumah')?->store('dokumen/rumah', 'public');
        $kkPath = $request->file('kk')?->store('dokumen/kk', 'public');

        // 3. Simpan ke tabel `alternatif` (Calon)
        $dataCalon = Alternatif::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'dusun' => $request->dusun,
            'status' => $request->status,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'tahun_masuk' => $request->tahun_masuk,
            'ktp' => $ktpPath,
            'rumah' => $rumahPath,
            'kk' => $kkPath,
            'id_pengguna' => Auth::id(),
        ]);

        // dd($calon);

        $id_alternatif = $dataCalon->id_alternatif;

        // dd($request->kriteria);

        // 4. Simpan penilaian ke tabel `penilaian`
        foreach ($request->kriteria as $id_kriteria => $id_sub_kriteria) {
            $sub = SubKriteria::find($id_sub_kriteria);
            
            Penilaian::create([
                'id_alternatif' => $dataCalon->id_alternatif, // atau $calon->id_alternatif, tergantung nama kolomnya
                'id_kriteria' => $id_kriteria,
                'id_sub_kriteria' => $id_sub_kriteria,
                'nilai' => $sub->nilai_rentang,
                'periode' => date('Y') // atau format lain sesuai sistemmu
            ]);
            
        }

        // 5. Redirect balik dengan pesan
        return redirect()->route('datacalon')->with('success', 'Data calon berhasil disimpan.');
    }

    public function EditCalon($id_alternatif = null)
    {
        // Ambil data calon berdasarkan ID jika ada
        $dataCalon = $id_alternatif ? Alternatif::findOrFail($id_alternatif) : null;

        // Ambil semua kriteria dan relasi subkriteria
        $dataKriteria = Kriteria::with('subKriteria')->get();

        // Ambil data penilaian untuk alternatif ini
        $dataPenilaian = $id_alternatif 
            ? Penilaian::where('id_alternatif', $id_alternatif)->get()
            : collect(); // kosongkan kalau form tambah

        return view('fcalonedit', compact('dataCalon', 'dataKriteria', 'dataPenilaian'));
    }


    public function UpdateCalon(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'nama' => 'required|string',
            'nik' => 'required|string',
            'dusun' => 'required|in:Bendungan,Made,Ngampel',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'tahun_masuk' => 'required|string',
            'ktp' => 'nullable|image|max:2048',
            'rumah' => 'nullable|image|max:2048',
            'kk' => 'nullable|image|max:2048',
            'kriteria' => 'required|array'
        ]);

        // 2. Cari data calon berdasarkan ID
        $dataCalon = Alternatif::findOrFail($id);

        // Blokir update kalau sudah diverifikasi
        if ($dataCalon->verifikasi === 'Terverifikasi') {
            return back()->with('error', 'Data sudah diverifikasi dan tidak bisa diubah.');
        }

        // 3. Upload file baru (jika ada) dan hapus file lama jika ada
        if ($request->hasFile('ktp')) {
            // Hapus file lama
            Storage::delete('public/' . $dataCalon->ktp);

            // Simpan file baru
            $ktpPath = $request->file('ktp')->store('dokumen/ktp', 'public');
            $dataCalon->ktp = $ktpPath;
        }

        if ($request->hasFile('rumah')) {
            // Hapus file lama
            Storage::delete('public/' . $dataCalon->rumah);

            // Simpan file baru
            $rumahPath = $request->file('rumah')->store('dokumen/rumah', 'public');
            $dataCalon->rumah = $rumahPath;
        }

        if ($request->hasFile('kk')) {
            // Hapus file lama
            Storage::delete('public/' . $dataCalon->kk);

            // Simpan file baru
            $kkPath = $request->file('kk')?->store('dokumen/kk', 'public');
            $dataCalon->kk = $kkPath;
        }

        // 4. Update data calon
        $dataCalon->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'dusun' => $request->dusun,
            'status' => $request->status,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'tahun_masuk' => $request->tahun_masuk,
            'id_pengguna' => Auth::id(),
        ]);

        // 5. Hapus penilaian lama (jika ada) dan simpan penilaian baru
        Penilaian::where('id_alternatif', $dataCalon->id_alternatif)->delete();

        foreach ($request->kriteria as $id_kriteria => $id_sub_kriteria) {
            $sub = SubKriteria::find($id_sub_kriteria);

            Penilaian::create([
                'id_alternatif' => $dataCalon->id_alternatif,
                'id_kriteria' => $id_kriteria,
                'id_sub_kriteria' => $id_sub_kriteria,
                'nilai' => $sub->nilai_rentang,
                'periode' => date('Y')
            ]);
        }

        // 6. Redirect balik dengan pesan
        return redirect()->route('datacalon')->with('success', 'Data calon berhasil diperbarui.');
    }

    public function HapusCalon($id_alternatif)
    {
        try {
            // Cari data calon berdasarkan ID
            $dataCalon = Alternatif::findOrFail($id_alternatif);

            if ($dataCalon->verifikasi === 'Terverifikasi') {
                return back()->with('error', 'Data terverifikasi tidak boleh dihapus.');
            }

            // Hapus data penilaian yang terkait dengan calon
            Penilaian::where('id_alternatif', $id_alternatif)->delete();

            // Hapus data calon dari tabel alternatif
            $dataCalon->delete();

            // Redirect dengan pesan sukses
            return redirect()->route('datacalon')
                ->with('success', 'Data calon berhasil dihapus');

        } catch (\Exception $e) {
            // Jika ada kesalahan, tampilkan pesan error
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data calon');
        }
    }

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
