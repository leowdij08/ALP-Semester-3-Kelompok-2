<?php

// PesanChat.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanChat extends Model
{
    use HasFactory;

    protected $table = 'pesans';

    protected $fillable = [
        'id_chat',
        'isi_pesan',
        'waktu_kirim',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'id_chat');
    }
}