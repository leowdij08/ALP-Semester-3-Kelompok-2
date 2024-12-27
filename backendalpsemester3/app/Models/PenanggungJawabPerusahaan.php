<?php

// PenanggungJawabPerusahaan.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenanggungJawabPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'penanggung_jawab_perusahaans';

    protected $fillable = [
        'id_perusahaan',
        'nama',
        'no_telepon',
        'email',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(UserPerusahaan::class, 'id_perusahaan');
    }
}