<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    protected $table = 'pemilik';
    protected $primaryKey = 'idpemilik';
    public $timestamps = false;

    protected $fillable = [
        'iduser',
        'alamat',
        'no_wa'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'idpemilik', 'idpemilik');
    }

    // Accessor untuk mendapatkan nama pemilik
    public function getNamaAttribute()
    {
        return $this->user ? $this->user->nama : 'N/A';
    }
}