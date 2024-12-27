<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrganisasi extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'user_organisasi';

    // Specify the primary key if it is not the default 'id'
    protected $primaryKey = 'id_organisasi';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'nama_organisasi',
        'alamat_organisasi',
        'telepon_organisasi',
        'email_organisasi',
    ];

    // Specify the relationship with the RekeningOrganisasi model
    public function rekeningOrganisasi()
    {
        return $this->hasMany(RekeningOrganisasi::class, 'id_organisasi', 'id_organisasi');
    }

    // You can define additional methods or scopes here if needed
}
