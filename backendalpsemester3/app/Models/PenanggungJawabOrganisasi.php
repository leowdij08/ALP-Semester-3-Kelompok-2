<?php

// PenanggungJawabOrganisasi.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenanggungJawabOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'penanggung_jawab_organisasis';

    protected $fillable = [
        'id_organisasi',
        'namalengkappjo',
        'tanggallahirpjo',
        'emailpjo',
        'alamatlengkappjo',
        'ktppjo',
    ];

    public function organisasis()
    {
        return $this->belongsTo(UserOrganisasi::class, 'id_organisasi');
    }
}