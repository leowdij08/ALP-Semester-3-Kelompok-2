<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrganisasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'namaorganisasi',
        'kotadomisiliorganisasi',
        'nomorteleponorganisasi',
        'id_user',
    ];
}
