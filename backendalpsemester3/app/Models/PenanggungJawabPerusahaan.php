<?php

// PenanggungJawabPerusahaan.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenanggungJawabPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'penanggung_jawab_perusahaan';

    protected $fillable = [
        'id_perusahaan',
        'namalengkappjp',
        'tanggallahirpjp',
        'emailpjp',
        'alamatlengkappjp',
        'ktppjp',
    ];

    public function perusahaans()
    {
        return $this->hasOne(UserPerusahaan::class, 'id_perusahaan');
    }
}
