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
        'nama',
        'no_telepon',
        'email',
    ];

    public function organisasi()
    {
        return $this->belongsTo(UserOrganisasi::class, 'id_organisasi');
    }
}