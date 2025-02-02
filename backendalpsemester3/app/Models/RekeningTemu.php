<?php

// RekeningTemu.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningTemu extends Model
{
    use HasFactory;

    protected $table = 'rekening_temus';

    protected $fillable = [
        'nomorrekeningtemu',
        'namabanktemu',
        'pemilikrekeningtemu',
    ];

}
