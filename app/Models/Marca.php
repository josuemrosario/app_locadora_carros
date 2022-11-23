<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    //aula 291
    protected $fillable = ['nome', 'imagem'];


    public function rules(){

        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem'=> 'required|file|mimes:png' //parametro precisa ser um arquivo
        ];

    }

    public function feedback(){

        return [
            'required' => 'O campo :attribute é obrigatório',
            'imagem.mimes' => 'arquivo deve ser to tipo png',
            'nome.unique' => 'Nome da marca já existe',
            'nome.min' => 'O nome deve possuir no mínimo 3 caracteres'
        ];

    }

    public function modelos(){
        //Uma marca possui muitos modelos
        return $this->hasMany('App\Models\Modelo');
    }
}

