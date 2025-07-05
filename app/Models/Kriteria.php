<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    public $timestamps = false;
    
    protected $fillable = [
        'id_kriteria',
        'nama_kriteria',
        'bobot_kriteria',
        'sifat_kriteria',

    ];

    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class, 'id_kriteria', 'id_kriteria');
    }

}
