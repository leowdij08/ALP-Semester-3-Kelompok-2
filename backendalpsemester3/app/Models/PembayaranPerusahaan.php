<?php

// PembayaranPerusahaan.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_perusahaans';

    protected $fillable = [
        'id_rekeningperusahaan',
        'id_acara'.
        'biayatotal',
        'tanggalpembayaran',
        'waktupembayaran',
        'buktipembayaran',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(RekeningPerusahaan::class, 'id_rekeningperusahaan');
    }
}