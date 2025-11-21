<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemuDokter extends Model
{
    protected $table = 'temu_dokter';
    protected $primaryKey = 'idreservasi_dokter';
    
    protected $fillable = [
        'no_urut',
        'waktu_daftar', 
        'status',
        'idpet',
        'idrole_user'
    ];
    
    public $timestamps = false;
    
    // Has one Rekam Medis
    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class, 'idreservasi_dokter', 'idreservasi_dokter');
    }
    
    // Belongs to Pet
    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }
    
    // Belongs to Role User
    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class, 'idrole_user', 'idrole_user');
    }
    
    // Belongs to User through RoleUser
    public function dokter()
    {
        return $this->hasOneThrough(
            User::class,
            RoleUser::class,
            'idrole_user', // Foreign key on role_user table
            'iduser', // Foreign key on user table
            'idrole_user', // Local key on temu_dokter table
            'iduser' // Local key on role_user table
        );
    }
    
    // Scope untuk antrian aktif
    public function scopeActive($query)
    {
        return $query->where('status', 0);
    }
    
    // Scope untuk antrian selesai
    public function scopeCompleted($query)
    {
        return $query->where('status', 1);
    }
    
    // Scope untuk antrian batal
    public function scopeCancelled($query)
    {
        return $query->where('status', 2);
    }
    
    // Method untuk cek apakah bisa diupdate status
    public function canUpdateStatus()
    {
        return $this->status === 0;
    }
}