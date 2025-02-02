<?php

// Example for Acara.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    use HasFactory;

    protected $table = 'event_organisasi'; // Table name based on migration

    protected $fillable = [
        'id_organisasi',
        'namaacara',
        'tanggalacara',
        'lokasiacara',
        'biayadibutuhkan',
        'kegiatanacara',
        'kotaberlangsung',
        'poster_event',
        'proposal',
    ];

    public function organisasis()
    {
        return $this->belongsTo(UserOrganisasi::class, 'id_organisasi', 'id_organisasi');
    }

    public function pembayarans()
    {
        return $this->hasMany(PembayaranPerusahaan::class, 'id_acara', 'id_acara');
    }
}
