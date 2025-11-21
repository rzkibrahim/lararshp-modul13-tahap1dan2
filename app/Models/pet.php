<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $table = 'pet';
    protected $primaryKey = 'idpet';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'warna_tanda',
        'jenis_kelamin',
        'idpemilik',
        'idras_hewan',
    ];

    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'idpemilik', 'idpemilik');
    }

    public function rasHewan()
    {
        return $this->belongsTo(RasHewan::class, 'idras_hewan', 'idras_hewan');
    }
    
    // One to Many dengan Rekam Medis
    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'idpet', 'idpet');
    }
    
    // One to Many dengan Temu Dokter
    public function temuDokter()
    {
        return $this->hasMany(TemuDokter::class, 'idpet', 'idpet');
    }
    
    // Accessor untuk mendapatkan nama pemilik melalui relasi
    public function getNamaPemilikAttribute()
    {
        return $this->pemilik && $this->pemilik->user 
            ? $this->pemilik->user->nama 
            : 'N/A';
    }
}