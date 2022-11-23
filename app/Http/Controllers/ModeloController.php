<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

//aula 321
use App\Repositories\ModeloRepository;

class ModeloController extends Controller
{

    public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(REQUEST $request)
    {

        $modeloRepository = new ModeloRepository($this->modelo);

        //trata o parametro atributos_marca enviados via URL
        if($request->has('atributos_marca')){

            $atributos_marca = 'marca:id,'.$request->atributos_marca;
            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_marca);

        }else{
            
            $modeloRepository->selectAtributosRegistrosRelacionados('marca');
        }

        //trata o parametro filtro enviados pela URL
        if($request->has('filtro')){

            $modeloRepository->filtro($request->filtro);
            

        }
    
        if($request->has('atributos')){
            $modeloRepository->selectAtributos($request->atributos);
            
        }

        return response()->json($modeloRepository->getResultado(), 200);        

        
        //----------------codigo velho anteiores a aula 321 ----------------//
        
        //aula 314 - selecionando apenas alguns atributos
        /*
        $modelos = array();

        if($request->has('atributos_marca')){

            $atributos_marca = $request->atributos_marca;
            $modelos = $this->modelo->with('marca:id,'.$atributos_marca);
        }else{
            $modelos = $this->modelo->with('marca');
        }
        */

        //aulas 316 e 317

        /*
        if($request->has('filtro')){
            
            $filtros = explode(';',$request->filtro);
            foreach($filtros as $key => $condicao){
                $c = explode(':',$condicao);
                $modelos = $modelos->where($c[0],$c[1],$c[2]);                
            }


        }

        if($request->has('atributos')){
            
            $atributos = $request->atributos;
            $modelos = $modelos->selectRaw($atributos)->get();
        }else{

            $modelos = $modelos->get();
        };
        
        //return response()->json($this->modelo->with('marca')->get(), 200);
        return response()->json($modelos, 200);
        */
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        
      
        $request->validate($this->modelo->rules());

        
        //recupera e persiste a imagem
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos','public'); 


        //cria o registro no banco
      

        $modelo = $this->modelo->create([ 
            'marca_id' => $request->marca_id,
            'nome' => $request ->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);
        

        

        return response()->json($modelo,201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        //busca o modelo via id
        //$modelo = $this->modelo->find($id); //alterado na aula 296
        $modelo = $this->modelo->with('marca')->find($id); //aula 312


        
        //será que o modelo existe ?
        if($modelo === null){

            return response()->json(['erro' => 'Recurso pesquisado não existe'],404);

        }

        //retorna o modelo
        return response()->json($modelo,200);        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modelo $modelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $modelo = $this->modelo->find($id);
        
        //Passo 1 - verifica se o modelo existe
        if($modelo === null){

            //return ['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'];
            //aula 298
            return response() ->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'],404);

        }

         // PASSO 2 - Cria e valida as regras
        if($request->method()==='PATCH'){
            

            //metodo PATCH (Valida apenas os campos enviados )
            $regrasDinamicas = array();

            //percorre as regras definidas no model
            foreach($modelo->rules() as $input => $regra){

                //cria o array de regras de validacao
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }

            }

            //efetua as validações necessarias
            $request->validate($regrasDinamicas);

        }else{

            //metodo PUT (Valida todos os campos )
            $request->validate($modelo->rules());
        }


        //PASSO 3 - MODIFICA O REGISTRO
        
        //Exclui a imagem antiga se existir
        
        
        
        if($request->file('imagem')){
            Storage::disk('public')->delete($modelo->imagem);
        }

        

        //persiste a nova imagem                
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos','public');  
        
    
        
        $modelo->fill($request->all());
        $modelo->imagem = $imagem_urn;
        
        
        //efetua o update na tabela
        $modelo->save();


        /*
        $modelo->update([ 
            'marca_id' => $request->marca_id,
            'nome' => $request ->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs    
	    ]);
        */

        //RETORNA O MODELO
        return response()->json($modelo,200);
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        //recupera o modelo
        $modelo = $this->modelo->find($id);


        //modelo foi encontrado ?
        if($modelo === null){
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'],404);
        }

        
        //remove o arquivo de imagem
        Storage::disk('public')->delete($modelo->imagem);


        //exclui o registro
        $modelo->delete();
        return response()->json(['msg' => 'modelo removido com sucesso'],200);



    }
}
