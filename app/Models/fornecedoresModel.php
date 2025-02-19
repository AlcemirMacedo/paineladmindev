<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class fornecedoresModel extends Model
{

    use HasFactory, Notifiable;
    public $timestamps = false;

    protected $table = 'tb_fornecedores';
    protected $fillabel = [
        'id_fornecedores',
        'nome',
        'razaosocial',
        'cpfcnpj',
        'endereco',
        'bairro',
        'cidade',
        'uf',
        'cep',
        'email',
        'telefone',
        'tipo_pessoa',
        'data_inclusao'
    ];
}
