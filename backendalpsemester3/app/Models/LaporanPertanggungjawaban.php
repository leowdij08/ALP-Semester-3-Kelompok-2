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
        'id_acara',
        'dokumentasilpj',
        'diterima',
        'revisike',
    ];

    public function acaras()
    {
        return $this->belongsTo(Acara ::class, 'id_acara');
    }
}
