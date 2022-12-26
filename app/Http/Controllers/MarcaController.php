<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

//aula 309
use Illuminate\Support\Facades\Storage;

//aula 320
use App\Repositories\MarcaRepository;

class MarcaController extends Controller
{
 
    
    // Aula 296
    public function __construct(Marca $marca){
        $this->marca = $marca;
    }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(REQUEST $request)
    {
        
        $marcaRepository = new MarcaRepository($this->marca);

        
        //trata o parametro atributos_modelos enviados via URL
        if($request->has('atributos_modelos')){

            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);

        }else{
            
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        //trata o parametro filtro enviados pela URL
        if($request->has('filtro')){

            $marcaRepository->filtro($request->filtro);
            

        }

        if($request->has('atributos')){
            $marcaRepository->selectAtributos($request->atributos);
            
        }

        return response()->json($marcaRepository->getResultadoPaginado(3), 200);

        //-----------------------logica velha--------------------
        //--Excluida a partir da aula 320

        /*
        $marcas = array();
        
        if($request->has('atributos_modelos')){

            $atributos_modelos = $request->atributos_modelos;
            $marcas = $this->marca->with('modelos:id,'.$atributos_modelos);
        }else{
            $marcas = $this->marca->with('modelos');
        }

        if($request->has('filtro')){
            
            $filtros = explode(';',$request->filtro);
            foreach($filtros as $key => $condicao){
                $c = explode(':',$condicao);
                $marcas = $marcas->where($c[0],$c[1],$c[2]);                
            }
        }

        
        if($request->has('atributos')){
            
            $atributos = $request->atributos;
            $marcas = $marcas->selectRaw($atributos)->get();
        }else{

            $marcas = $marcas->get();
        };

        */
        
        //aula 292 selecionando registros via get
        //$marcas = $this->marca->with('modelos')->get(); //alterado na aula 296
        
        //return $marcas;
        //aula 298
        
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
        
        //validação de dados
        $request->validate($this->marca->rules(), $this->marca->feedback());

        
        // Aula 291
        //$marca = Marca::create($request->all());
        

        //dd($request->nome);
        //dd($request->get('nome'));
        //dd($request->input('nome'));

        //dd($request->imagem);

        //Aula 304 upload de arquivos parte 2
        $imagem = $request->file('imagem'); //recupera a imagem
             //Segundo parametro disco foi omitido mas é configurado
             //usando o arquivos config\filesystems
             //quando omitido ele fica em local (storage\images)
             //$image->store('imagens/x/y/z','public') armazena dentro da subpastas z

        $imagem_urn = $imagem->store('imagens','public'); //persiste a imagem
        
        //aula 303
        //return $marca;
        //$marca = $this->marca->create($request->all()); //alterado na aula 296
        
        $marca = $this->marca->create([ //alterado na aula 305 para salvar o caminho da imagem no banco
            'nome' => $request ->nome,
            'imagem' => $imagem_urn

        ]);

        //segunda opcao
        //$marca->nome = $request->nome;
        //$marca->imagem = $imagem_urn;
        //$marca->save();

        //aula 298        
        return response()->json($marca,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //aula 292
        
        //$marca = $this->marca->find($id); //alterado na aula 296
        $marca = $this->marca->with('modelos')->find($id); //aula 312
        

        //aula 297 
        if($marca === null){

            //return ['erro' => 'Recurso pesquisado não existe'];

            //aula 298
            return response()->json(['erro' => 'Recurso pesquisado não existe'],404);

        }

        return response()->json($marca,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) //alterado na aula 296
    {
        //aula 293 - atualiando registros via put e patch
        
        /*
        print_r($request->all()); //dados atualizados
        echo '<hr>';
        print_r($marca->getAttributes()); //dados antigos
        */

        //$marca->update($request->all());
        $marca = $this->marca->find($id);

        //aula 297
        if($marca === null){

            //return ['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'];
            //aula 298
            return response() ->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'],404);

        }

        //aula 302


        if($request->method()==='PATCH'){
            

            //metodo PATCH (Valida apenas os campos enviados )
            $regrasDinamicas = array();

            //percorre as regras definidas no model
            foreach($marca->rules() as $input => $regra){

                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }

            }

            $request->validate($regrasDinamicas,$marca->feedback());

        }else{

            //metodo PUT (Valida todos os campos )
            $request->validate($marca->rules(),$marca->feedback());
        }

        
        //aula 309 - removendo imagens antigas
        // se o arquivo foi encaminhado no update então remove o antigo
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
        }

        
        //aula 308
        //ATENÇAO para fazer update
        // usar o método POST
        // Acrescentar o parametros _method com o valor PUT ou PATCH
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens','public');   

        $marca->fill($request->all());
        $marca->imagem = $imagem_urn;


        
        //aula 313
        $marca->save();
        
        
        /*
        $marca->update([ 
            'nome' => $request ->nome,
	        'imagem' => $imagem_urn
	    ]);
        */


        return response()->json($marca,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        
        //Aula 294 removendo registros via DELETE
        //$marca->delete();

        $marca = $this->marca->find($id);

        
        //aula 297
        if($marca === null){

            //return ['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'];

            //aula 298
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'],404);

            
        }

        //aula 309 - removendo imagens antigas
        // se o arquivo foi encaminhado no update então remove o antigo        
        Storage::disk('public')->delete($marca->imagem);

        

        $marca->delete();

        return response()->json(['msg' => 'marca removida com sucesso'],200);
    }
}
