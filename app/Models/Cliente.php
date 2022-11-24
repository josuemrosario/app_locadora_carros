<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    //Descricao: Cria as regras de negócio genéricas que são usadas pelo controlador
    public function rules(){

        return [
            'nome' => 'required'
        ];

    }

}
