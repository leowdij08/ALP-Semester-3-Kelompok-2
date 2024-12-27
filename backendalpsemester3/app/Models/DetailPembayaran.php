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
        'id_detailpembayaran',
        'id_pembayaran',
        'jumlah',
        'tanggal_pembayaran',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(PembayaranPerusahaan::class, 'id_pembayaran');
    }
}