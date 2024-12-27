<?php

// LampiranPesan.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LampiranPesan extends Model
{
    use HasFactory;

    protected $table = 'lampiran_pesans'; // Table name based on migration

    protected $fillable = [
        'id_pesan',
        'file_path',
        'file_type',
    ];

    public function pesan()
    {
        return $this->belongsTo(PesanChat::class, 'id_pesan');
    }
}