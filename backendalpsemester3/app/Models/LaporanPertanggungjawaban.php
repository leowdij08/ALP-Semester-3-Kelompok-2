<?php

// LaporanPertanggungjawaban.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPertanggungjawaban extends Model
{
    use HasFactory;

    protected $table = 'laporan_pertanggung_jawabans';

    protected $fillable = [
        'id_organisasi',
        'judul',
        'deskripsi',
        'file_path',
    ];

    public function organisasi()
    {
        return $this->belongsTo(UserOrganisasi::class, 'id_organisasi');
    }
}
