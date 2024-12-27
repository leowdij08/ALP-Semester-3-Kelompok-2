<?php

// PenarikanOrganisasi.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenarikanOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'penarikan_organisasis';

    protected $fillable = [
        'id_rekeningorganisasi',
        'jumlah',
        'tanggal',
    ];

    public function organisasi()
    {
        return $this->belongsTo(RekeningOrganisasi::class, 'id_rekeningorganisasi');
    }
}