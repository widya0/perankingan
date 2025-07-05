<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class Pengguna extends Authenticatable  
{
    use HasFactory, Notifiable;
    
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    public $timestamps = false;
    
    protected $fillable = [
        'id_pengguna',
        'username',
        'password',
        'level',

    ];

}
