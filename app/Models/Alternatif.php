<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    protected $table = 'alternatif';
    protected $primaryKey = 'id_alternatif';
    public $timestamps = false;
    
    protected $fillable = [
        'id_alternatif',
        'nama',
        'nik',
        'dusun',
        'rt',
        'rw',
        'tahun_masuk',
        'status',
        'rumah',
        'ktp',
        'kk',
        'verifikasi',
        'id_pengguna',
    ];

    public function penerimaBantuan()
    {
        return $this->hasMany(PenerimaBantuan::class, 'id_alternatif');
    }
// Alternatif.php (Model)
public static function filterDusun($query = null, $dusun = null)
{
    $query = $query ?? self::query();

    if ($dusun) {
        $query->where('dusun', $dusun);
    }

    return $query;
}


    public static function getDusunListAktif()
    {
        return self::where('status', 'Aktif')
            ->select('dusun')
            ->distinct()
            ->orderBy('dusun')
            ->pluck('dusun');
    }

}
