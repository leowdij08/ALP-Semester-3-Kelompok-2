<?php

// Example for Acara.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    use HasFactory;

    protected $table = 'acaras'; // Table name based on migration

    protected $fillable = [
        'id_organisasi',
        'namaacara',
        'tanggalacara',
        'lokasiacara',
        'biayadibutuhkan',
        'kegiatanacara',
        'kotaberlangsung',
    ];

    public function organisasis()
    {
        return $this->belongsTo(UserOrganisasi::class, 'id_organisasi');
    }
}
