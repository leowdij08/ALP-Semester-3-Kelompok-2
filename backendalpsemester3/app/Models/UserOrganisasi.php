<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'user_organisasi';

    protected $fillable = [
        'id_user',
        'namaorganisasi',
        'kotadomisiliorgansiasi',
        'nomorteleponorganisasi',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function acaras()
    {
        return $this->hasMany(Acara::class, 'id_organisasi', 'id_organisasi');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'id_organisasi');
    }

    public function penanggungjawab()
    {
        return $this->hasOne(PenanggungJawabOrganisasi::class, 'id_organisasi');
    }

    public function rekeningorganisasis()
    {
        return $this->hasMany(RekeningOrganisasi::class, 'id_organisasi');
    }
}
