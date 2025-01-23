<?php

// PesanChat.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanChat extends Model
{
    use HasFactory;

    protected $table = 'pesan_chat';

    protected $fillable = [
        'id_chat',
        'pengirimisperusahaan',
        'waktukirim',
        'dibaca',
        'waktubaca',
        'isipesan'
    ];

    public function chats()
    {
        return $this->belongsTo(Chat::class, 'id_chat');
    }

    public function lampirans()
    {
        return $this->hasOne(LampiranPesan::class, 'id_pesan');
    }
}
