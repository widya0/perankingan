<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PenerimaBantuan extends Model
{
    protected $table = 'penerima_bantuan';
    protected $primaryKey = 'id_penerima_bantuan';
    public $timestamps = false;
    
    protected $fillable = [
        'id_penerima_bantuan',
        'tahun_seleksi',
        'nilai_hasil',
        'status_penerima',
        'id_alternatif'
    ];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif', 'id_alternatif');
    }

    public static function filterTahun($tahun = null)
    {
        $query = self::query();

        if ($tahun) {
            $query->where('tahun_seleksi', $tahun);
        }

        return $query;
    }

        public static function filterStatus($status_penerima = null)
    {
        $query = self::query();

        if ($status_penerima) {
            $query->where('status_penerima', $status_penerima);
        }

        return $query;
    }


    public static function sudahPernahDiterima($id_alternatif, $tahun) {
        return self::where('id_alternatif', $id_alternatif)
            ->where('status_penerima', 'Diterima')
            ->where('tahun_seleksi', '>=', $tahun - 5)
            ->exists();
    }

    public static function getTahunDiterima()
    {
        return self::select('tahun_seleksi')
            ->distinct()
            ->pluck('tahun_seleksi')
            ->map(fn($tahun) => (string) $tahun)
            ->sort()
            ->values();
    }

    public static function getJumlahPerTahun()
    {
        $labels = self::getTahunDiterima()->map(fn($tahun) => (string) $tahun); // pastikan string
        $data = [];
        foreach ($labels as $tahun) {
            $data[] = self::where('tahun_seleksi', $tahun)
                ->where('status_penerima', 'Diterima')
                ->count();
        }
        return [$labels, $data];
    }

    public static function getSummaryPerDusun()
    {
        return self::select('alternatif.dusun', DB::raw('count(*) as total'))
            ->join('alternatif', 'alternatif.id_alternatif', '=', 'penerima_bantuan.id_alternatif')
            ->where('status_penerima', 'Diterima')
            ->groupBy('alternatif.dusun')
            ->pluck('total', 'alternatif.dusun');
    }

    public static function TampilDashboard($tahun, $aksi)
    {
        self::where('aksi', 'Tampil')->update(['aksi' => 'Sembunyi']);

        return self::where('tahun_seleksi', $tahun)
            ->update(['aksi' => $aksi]); // langsung update dengan string 'Tampil' atau 'Sembunyi'
    }

}
