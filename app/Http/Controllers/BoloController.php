<?php

namespace App\Http\Controllers;

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

     /**
     * Método para listar todos os bolos e seus interessados salvos no banco
     * @return ResponseJson: o retorno pode ser de duas formas:
     *  1-)Array com dados: caso haja dados salvos no banco de dados
     *  2-)Array vazio: caso não haja dados no banco de dados
     */

    public function index()
    {
       return $this->service->indexBolo();
    }


     /**
     * Método para registrar no banco um novo bolo com e-mails de interessados associados.
     * @param nome         (required|string|unique) nome do bolo
     * @param peso         (required|numeric|between:0,999999.99) peso do bolo (em gramas)
     * @param valor        (required|numeric|between:0,999999999.99) valor do bolo
     * @param quantidade   (nullable|integer) quantidade disponível (default=0)
     * @param interessados (nullable|array) lista de e-mails dos interessados
     * @return ResponseJson: o retorno pode ser de duas formas:
     *  1-)Sucesso: caso os dados sejam salvos corretamente no banco de dados
     *  2-)Error:
     *    a-)Exceção InvalidArgumentException: quando o sistema identifica que um ou mais valores estão incorretos, de acordo com a validação
     *    b-)Exceção Genérica Exception: quando o sistema tenta cadastrar os dados no banco, porém, ocorre algum erro não previsto
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
     * Método para buscar um bolo específico juntamente com seus interessados
     * @param id (integer) id do bolo registrado no banco de dados
     * @return ResponseJson: o retorno pode ser de duas formas:
     *  1-)Sucesso: os dados do bolo específico com seus interessados
     *  2-)Error:
     *    a-)Exceção Genérica Exception: quando o sistema não encontra o bolo a partir do id enviado
     */

    public function edit($id)
    {
        try{
            return $this->service->editBolo($id);
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage(),'status'=>404],404);
        }
    }

     /**
     * Método para atualizar um bolo específico com e-mails de interessados associados.
     * @param nome         (required|string|unique) nome do bolo
     * @param peso         (required|numeric|between:0,999999.99) peso do bolo (em gramas)
     * @param valor        (required|numeric|between:0,999999999.99) valor do bolo
     * @param quantidade   (nullable|integer) quantidade disponível (default=0)
     * @param interessados (nullable|array) lista de e-mails dos interessados (novos ou antigos). Obs: Se um ou mais interessado(s) que estava(m) salvos não for(rem) passado na atualização, os mesmos são excluídos(sync)
     * @return ResponseJson: o retorno pode ser de duas formas:
     *  1-)Sucesso: caso os dados sejam atualizados corretamente no banco de dados
     *  2-)Error:
     *     a-)Exceção InvalidArgumentException: quando o sistema identifica que um ou mais valores estão incorretos, de acordo com a validação
     *     b-)Exceção Genérica Exception: quando o sistema tenta atualizar os dados no banco, porém, ocorre algum erro não previsto
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
     * Método para excluir um bolo específico juntamente com seus interessados
     * @param id (integer) id do bolo registrado no banco de dados
     * @return ResponseJson: o retorno pode ser de duas formas:
     *  1-)Sucesso: se os dados forem excluídos corretamente.
     *  2-)Error:
     *    a-)Exceção Genérica Exception: quando o sistema tenta excluir os dados no banco, porém, ocorre algum erro não previsto
     */

    public function destroy($id)
    {
        try{
            $this->service->delete($id);            
            return response()->json(['msg'=>'Bolo deletado com sucesso!','status'=>200],200);
         }catch(Exception $e){
             return response()->json(['msg'=>'Não foi possível realizar a exclusão do bolo','status'=>500],500);
         }
    }
}
