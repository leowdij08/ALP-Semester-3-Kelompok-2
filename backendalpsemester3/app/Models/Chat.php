<?php

// Chat.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chat'; // Table name based on migration

    protected $fillable = [
        'id_perusahaan',
        'id_organisasi',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(UserPerusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function organisasi()
    {
        return $this->belongsTo(UserOrganisasi::class, 'id_organisasi', 'id_organisasi');
    }
}
