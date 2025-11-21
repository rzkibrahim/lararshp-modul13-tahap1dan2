<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRekamMedis extends Model
{
    protected $table = 'detail_rekam_medis';
    protected $primaryKey = 'iddetail_rekam_medis';
    
    protected $fillable = ['idrekam_medis', 'idkode_tindakan_terapi', 'detail'];
    
    // Belongs to Rekam Medis
    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'idrekam_medis', 'idrekam_medis');
    }
    
    // Belongs to Kode Tindakan Terapi
    public function kodeTindakanTerapi()
    {
        return $this->belongsTo(KodeTindakanTerapi::class, 'idkode_tindakan_terapi', 'idkode_tindakan_terapi');
    }
    
    // ✅ TAMBAHKAN: Akses ke temuDokter melalui rekamMedis
    public function temuDokter()
    {
        return $this->hasOneThrough(
            TemuDokter::class,
            RekamMedis::class,
            'idrekam_medis', // Foreign key on rekam_medis table
            'idreservasi_dokter', // Foreign key on temu_dokter table
            'idrekam_medis', // Local key on detail_rekam_medis table
            'idreservasi_dokter' // Local key on rekam_medis table
        );
    }
}