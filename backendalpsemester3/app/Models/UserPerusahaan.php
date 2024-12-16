<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPerusahaan extends Model
{
    use HasFactory;
    protected $fillable = [
        'namaperusahaan',
        'kotadomisiliperusahaan',
        'nomorteleponperusahaan',
    ];
}
