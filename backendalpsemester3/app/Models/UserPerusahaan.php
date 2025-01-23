<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'user_perusahaan';

    protected $fillable = [
        'namaperusahaan',
        'emailperusahaan',
        'kotadomisiliperusahaan',
        'nomorteleponperusahaan',
        'katasandiperusahaan',
    ];

    public function chats()
    {
        return $this->hasMany(Chat::class, 'id_perusahaan');
    }

    public function rekeningperusahaans()
    {
        return $this->hasOne(RekeningPerusahaan::class, 'id_perusahaan');
    }

    public function penanggungjawab()
    {
        return $this->belongsTo(PenanggungJawabPerusahaan::class, 'id_perusahaan');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
