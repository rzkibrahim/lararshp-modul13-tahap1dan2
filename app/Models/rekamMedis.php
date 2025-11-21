<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';
    protected $primaryKey = 'idrekam_medis';
    
    protected $fillable = [
        'idreservasi_dokter',
        'idpet', 
        'created_at', 
        'anamnesa', 
        'temuan_klinis', 
        'diagnosa', 
        'dokter_pemeriksa'
    ];
    
    public $timestamps = false;
    
    // ✅ TAMBAHKAN: Casting untuk created_at
    protected $casts = [
        'created_at' => 'datetime',
    ];
    
    // Belongs to Pet
    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }
    
    // Has many Detail Rekam Medis
    public function detailRekamMedis()
    {
        return $this->hasMany(DetailRekamMedis::class, 'idrekam_medis', 'idrekam_medis');
    }
    
    // Belongs to Role User (Dokter)
    public function dokter()
    {
        return $this->belongsTo(RoleUser::class, 'dokter_pemeriksa', 'idrole_user');
    }
    
    // ✅ TAMBAHKAN: Belongs to TemuDokter
    public function temuDokter()
    {
        return $this->belongsTo(TemuDokter::class, 'idreservasi_dokter', 'idreservasi_dokter');
    }
    
    // ✅ TAMBAHKAN: Scope untuk rekam medis aktif/selesai
    public function scopeActive($query)
    {
        return $query->whereHas('temuDokter', function($q) {
            $q->where('status', 0);
        });
    }
    
    public function scopeCompleted($query)
    {
        return $query->whereHas('temuDokter', function($q) {
            $q->where('status', 1);
        });
    }
}