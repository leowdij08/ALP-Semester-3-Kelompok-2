<?php

// RekeningOrganisasi.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'rekening_organisasis';

    protected $fillable = [
        'id_organisasi',
        'nomor_rekening',
        'bank',
        'atas_nama',
    ];

    public function organisasi()
    {
        return $this->belongsTo(UserOrganisasi::class, 'id_organisasi');
    }
}


