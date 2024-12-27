<?php

// DetailPembayaran.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembayaran extends Model
{
    use HasFactory;

    protected $table = 'detail_pembayarans'; // Table name based on migration

    protected $fillable = [
        'id_pembayaran',
        'id_rekeningtemu',
        'biayasponsor',
        'biayalayananaplikasi',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(PembayaranPerusahaan::class, 'id_pembayaran');
    }

    public function RekeningTemu()
    {
        return $this->belongsTo(RekeningTemu::class, 'id_rekeningtemu');
    }
}