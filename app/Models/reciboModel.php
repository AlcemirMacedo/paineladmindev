<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class reciboModel extends Model
{
    use HasFactory, Notifiable;
    public $timestamps = false;

    protected $table = 'tbrecibo';
    protected $fillabel = [
        'id_recibo',
        'num_recibo',
        'cpfcnpj_recibo',
        'desc_recibo',
        'valor_recibo',
        'data_recibo',
        'vlr_extenso'
    ];
}
