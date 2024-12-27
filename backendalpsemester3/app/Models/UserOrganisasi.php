<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'user_organisasis';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'alamat',
        'no_telepon',
    ];

    public function acaras()
    {
        return $this->hasMany(Acara::class, 'id_organisasi');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'id_organisasi');
    }
}
