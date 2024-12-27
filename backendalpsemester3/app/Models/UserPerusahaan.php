<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'user_perusahaans';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'alamat',
        'no_telepon',
    ];

    public function chats()
    {
        return $this->hasMany(Chat::class, 'id_perusahaan');
    }
}