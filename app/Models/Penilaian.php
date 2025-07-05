<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $primaryKey = 'id_penilaian';
    public $timestamps = false;
    
    protected $fillable = [
        'id_alternatif',
        'id_kriteria',
        'id_sub_kriteria',
        'nilai',
        'periode'

    ];

    public function Alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif', 'id_alternatif');
    }

    public function SubKriteria()
    {
        return $this->belongsTo(SubKriteria::class, 'id_sub_kriteria', 'id_sub_kriteria');
    }

    public function Kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
