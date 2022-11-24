<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{

    public function __construct(Cliente $cliente){
        $this->cliente = $cliente;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        
         //trata o parametro filtro enviados pela URL
        if($request->has('filtro')){

            $clienteRepository->filtro($request->filtro);
            

        }

        if($request->has('atributos')){
            $clienteRepository->selectAtributos($request->atributos);
            
        }

        return response()->json($clienteRepository->getResultado(), 200);

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validação de dados
        $request->validate($this->cliente->rules());
        
        //persiste um objeto cliente
        $cliente = $this->cliente->create([ 
            'nome' => $request->nome
        ]);

        //Retorna o objeto que foi persistido
        return response()->json($cliente,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->find($id);
        
        
        //Confirma se o modelo de cliente existe
        if($cliente === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'],404);
        }

        return response()->json($cliente,200);     
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClienteRequest  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente === null){

            return response() ->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'],404);

        }


        //faz atualizações de acordo com o verbo HTTP
        // PATCH - Apenas parte dos dados serão modificados
        // PUT - Todos os dados serão modificados
        if($request->method()==='PATCH'){
            

            //metodo PATCH (Valida apenas os campos enviados )
            $regrasDinamicas = array();

            //percorre as regras definidas no model
            foreach($cliente->rules() as $input => $regra){

                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }

            }

            $request->validate($regrasDinamicas);

        }else{

            //metodo PUT (Valida todos os campos )
            $request->validate($cliente->rules());
        }

        $cliente->fill($request->all());
        $cliente->save();        
        return response()->json($cliente,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente === null){

            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'],404);
            
        }

        $cliente->delete();
        return response()->json(['msg' => 'O cliente foi removido com sucesso'],200);
    }
}
