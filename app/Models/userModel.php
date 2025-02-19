<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class userModel extends Model
{
    use HasFactory, Notifiable;
    public $timestamps = false;

    protected $table = 'tbuser';
    protected $fillabel = [
        'id_usuario',
        'nome',
        'login',
        'senha',
        'email'
    ];

}
