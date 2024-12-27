<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPerusahaan extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'perusahaan';

    // Specify the primary key if it is not the default 'id'
    protected $primaryKey = 'id_perusahaan';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'nama_perusahaan',
        'alamat_perusahaan',
        'telepon_perusahaan',
        'email_perusahaan',
    ];

    // Specify the relationship with the RekeningOrganisasi model
    public function rekeningOrganisasi()
    {
        return $this->hasMany(RekeningOrganisasi::class, 'id_organisasi', 'id_perusahaan');
    }

    // You can define additional methods or scopes here if needed
}
