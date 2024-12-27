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
        'nomorrekeningorganisasi',
        'namabankorganisasi',
        'pemilikrekeningorganisasi',
    ];

    public function organisasis()
    {
        return $this->belongsTo(UserOrganisasi::class, 'id_organisasi');
    }
}


