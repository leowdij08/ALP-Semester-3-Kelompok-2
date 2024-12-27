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
        'pengirimisperusahaan',
        'waktu_kirim',
        'dibaca',
        'waktubaca',
        'isipesan'
    ];

    public function chats()
    {
        return $this->belongsTo(Chat::class, 'id_chat');
    }
}