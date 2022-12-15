<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //aula 330

    public function login(Request $request){   
        
        //aula 331 - autenticacao de usuarios
    
        //Autentica usuario
        $credenciais = $request->all(['email','password']);
        $token = auth('api')->attempt($credenciais);      
        
        if(!$token){
            return response()->json(['erro' => 'usuario ou senha invalidos'], 403);
        }

        // Retorna um Json Web Token (JWT)
        return response()->json(['token' => $token]);
    }
    
    public function logout(){   
        //return 'logout';
        //aula 338
        auth('api')->logout();
        return response()->json(['msg' => 'logout realizado com sucesso']);
    }

    public function refresh(){   
        //return 'refresh';
        //aula 337
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }

    public function me(){   
        //return 'me';
        //aula 336 recuperando dados do usuario
        return response()->json(auth()->user());
    }
}
 