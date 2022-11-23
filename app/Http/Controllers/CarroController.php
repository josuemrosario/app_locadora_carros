<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use App\Repositories\CarroRepository;

class CarroController extends Controller
{

    public function __construct(Carro $carro){
        $this->carro = $carro;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(REQUEST $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        
        //trata o parametro atributos_modelos enviados via URL
        if($request->has('atributos_modelo')){

            $atributos_modelos = 'modelo:id,'.$request->atributos_modelos;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);

        }else{
            
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }

        //trata o parametro filtro enviados pela URL
        if($request->has('filtro')){

            $carroRepository->filtro($request->filtro);
            

        }

        if($request->has('atributos')){
            $carroRepository->selectAtributos($request->atributos);
            
        }

        return response()->json($carroRepository->getResultado(), 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
        //validação de dados
        $request->validate($this->carro->rules());
        
        //persiste um objeto carro
        $carro = $this->carro->create([ 
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km

        ]);

        //Retorna o objeto que foi persistido
        return response()->json($carro,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        
        
        //Confirma se o modelo de carro existe
        if($carro === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'],404);
        }

        return response()->json($carro,200);        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function edit(Carro $carro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $carro = $this->carro->find($id);

        if($carro === null){

            return response() ->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'],404);

        }


        //faz atualizações de acordo com o verbo HTTP
        // PATCH - Apenas parte dos dados serão modificados
        // PUT - Todos os dados serão modificados
        if($request->method()==='PATCH'){
            

            //metodo PATCH (Valida apenas os campos enviados )
            $regrasDinamicas = array();

            //percorre as regras definidas no model
            foreach($carro->rules() as $input => $regra){

                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }

            }

            $request->validate($regrasDinamicas);

        }else{

            //metodo PUT (Valida todos os campos )
            $request->validate($carro->rules());
        }

        $carro->fill($request->all());
        $carro->save();        
        return response()->json($carro,200);
        
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if($carro === null){

            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'],404);
            
        }

        $carro->delete();
        return response()->json(['msg' => 'O carro foi removido com sucesso'],200);
    }
}

?>
