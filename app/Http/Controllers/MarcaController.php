<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

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
    public function index()
    {
        //aula 292 selecionando registros via get
        $marcas = $this->marca->all(); //alterado na aula 296
        
        //return $marcas;
        //aula 298
        return response()->json($marcas, 200);
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
        $marca = $this->marca->create($request->all()); //alterado na aula 296
        
        //return $marca;

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
        
        $marca = $this->marca->find($id); //alterado na aula 296

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

        $marca->update($request->all());

        return response()->json($marca,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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

        $marca->delete();

        return response()->json(['msg' => 'marca removida com sucesso'],200);
    }
}
