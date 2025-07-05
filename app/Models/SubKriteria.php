<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    protected $table = 'sub_kriteria';
    protected $primaryKey = 'id_sub_kriteria';
    public $timestamps = false;
    
    protected $fillable = [
        'id_sub_kriteria',
        'rentang',
        'nilai_rentang',
        'id_kriteria',

    ];
    public function Kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }

}
