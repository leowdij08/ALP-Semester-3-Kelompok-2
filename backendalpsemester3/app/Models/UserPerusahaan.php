<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'user_perusahaans';

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

    public function laporanpertanggungjawabans()
    {
        return $this->hasMany(LaporanPertanggungjawaban::class, 'id_perusahaan');
    }

    public function rekeningperusahaans()
    {
        return $this->hasMany(RekeningPerusahaan::class, 'id_perusahaan');
    }

    public function penanggungjawabperusahaans()
    {
        return $this->hasOne(PenanggungJawabPerusahaan::class, 'id_perusahaan');
    }
}