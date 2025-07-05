<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSAW extends Model
{
    protected $table = 'hasil_saw';
    protected $primaryKey = 'id_hasil_saw';
    public $timestamps = false;
    
    protected $fillable = [
        'id_hasil_saw',
        'nilai_hasil_saw',
        'ranking',
        'id_alternatif',

    ];

    public static function simpanRankingBaru($ranking)
    {
        self::truncate();
        $no = 1;
        foreach ($ranking as $id => $nilai) {
            self::create([
                'id_alternatif' => $id,
                'nilai_hasil_saw' => $nilai,
                'ranking' => $no++,
            ]);
        }
    }

}
