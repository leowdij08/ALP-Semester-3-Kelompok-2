<?php

// DetailWaktuAcara.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailWaktuAcara extends Model
{
    use HasFactory;

    protected $table = 'detail_waktu_acaras'; // Table name based on migration

    protected $fillable = [
        'id_acara',
        'waktu_mulai',
        'waktu_selesai',
    ];

    public function acara()
    {
        return $this->belongsTo(Acara::class, 'id_acara');
    }
}