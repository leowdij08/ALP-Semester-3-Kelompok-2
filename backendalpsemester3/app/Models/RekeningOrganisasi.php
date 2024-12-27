<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningOrganisasi extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'rekening_organisasi';

    // Specify the primary key if it is not the default 'id'
    protected $primaryKey = 'id_rekeningorganisasi';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'nomorrekeningorganisasi',
        'namabankorganisasi',
        'pemilikrekeningorganisasi',
        'isActive',
        'id_organisasi',
    ];

    // Specify the relationship with the UserOrganisasi model
    public function organisasi()
    {
        return $this->belongsTo(UserOrganisasi::class, 'id_organisasi', 'id_organisasi');
    }

    // You can define additional methods or scopes here if needed
}
