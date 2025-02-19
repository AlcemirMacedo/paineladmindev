<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class rdvModel extends Model
{
    use HasFactory, Notifiable;
    public $timestamps=false;

    protected $table = "tb_rdv";
    protected $fillabel= [
        'id_rdv',
        'num_rdv',
        'desc_rdv',
        'qtd_rdv',
        'vlr_rdv',
        'obs_rdv',
        'data_rdv'
    ];
}
