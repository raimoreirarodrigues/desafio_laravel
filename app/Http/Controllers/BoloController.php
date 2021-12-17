<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoloCollection;
use App\Http\Resources\BoloResource;
use App\Models\Bolo;
use App\Models\BoloInteressado;
use App\Services\BoloService;
use Exception;
use Illuminate\Http\Request;
use InvalidArgumentException;


class BoloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */

     private $service;

    public function __construct(BoloService $service){
        $this->service = $service;
    }

    public function index()
    {
        return new BoloCollection(Bolo::get());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
           $this->service->create($request->all());            
           return response()->json(['msg'=>'Bolo cadastrado com sucesso!','status'=>201],201);
        }catch(InvalidArgumentException $e){
            return response()->json(['msg'=>$e->getMessage(),'status'=>400],400);
        }catch(Exception $e){
            return response()->json(['msg'=>'Não foi possível realizar o cadastro do bolo','status'=>500],500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return new BoloResource(Bolo::findOrFail($id));
        }catch(Exception $e){
            return response()->json(['msg'=>'Nenhum bolo encontrado','status'=>404],404);
        }
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $this->service->update($request->all(),$id);            
            return response()->json(['msg'=>'Bolo atualizado com sucesso!','status'=>201],201);
         }catch(InvalidArgumentException $e){
             return response()->json(['msg'=>$e->getMessage(),'status'=>400],400);
         }catch(Exception $e){
             return response()->json(['msg'=>'Não foi possível realizar a atualização do bolo','status'=>500],500);
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $this->service->delete($id);            
            return response()->json(['msg'=>'Bolo deletado com sucesso!','status'=>201],201);
         }catch(Exception $e){
             return response()->json(['msg'=>'Não foi possível realizar a exclusão do bolo','status'=>500],500);
         }
    }
}
