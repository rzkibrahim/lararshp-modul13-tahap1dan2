<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisHewan extends Model
{
    protected $table = 'jenis_hewan';
    protected $primaryKey = 'idjenis_hewan';
    public $timestamps = false; 
    
    protected $fillable = ['nama_jenis_hewan'];
    
    public function rasHewan()
    {
        return $this->hasMany(RasHewan::class, 'idjenis_hewan', 'idjenis_hewan');
    }
    
    // Method untuk cek apakah bisa dihapus
    public function canBeDeleted()
    {
        return !$this->rasHewan()->exists();
    }
    
    // Method untuk mendapatkan pesan error jika tidak bisa dihapus
    public function getDeleteErrorMessage()
    {
        return 'Jenis hewan tidak dapat dihapus karena masih memiliki data ras hewan.';
    }
    
    // Method untuk mendapatkan jumlah ras hewan
    public function getJumlahRasAttribute()
    {
        return $this->rasHewan()->count();
    }
}