<?php

// RekeningPerusahaan.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'rekening_perusahaans';

    protected $fillable = [
        'id_perusahaan',
        'nomor_rekening',
        'bank',
        'atas_nama',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(UserPerusahaan::class, 'id_perusahaan');
    }
}