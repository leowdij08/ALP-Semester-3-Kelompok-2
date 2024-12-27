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
        'id_penarikan',
        'id_rekeningorganisasi',
        'jumlahdanaditarik',
        'tanggalpenarikan',
        'waktupenarikan',
        'buktipenarikan',
    ];

    public function rekeningorganisasis()
    {
        return $this->belongsTo(RekeningOrganisasi::class, 'id_rekeningorganisasi');
    }
}