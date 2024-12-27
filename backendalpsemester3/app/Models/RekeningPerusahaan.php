<?php

// RekeningPerusahaan.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'rekening_perusahaans';

    protected $fillable = [
        'id_perusahaan',
        'nomorrekeningperusahaan',
        'namabankperusahaan',
        'pemilikrekeningperusahaan',
    ];

    public function perusahaans()
    {
        return $this->belongsTo(UserPerusahaan::class, 'id_perusahaan');
    }
}