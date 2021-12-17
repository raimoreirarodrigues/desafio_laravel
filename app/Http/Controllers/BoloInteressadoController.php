<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoloInteressadoCollection;
use App\Models\BoloInteressado;
use App\Services\BoloService;
use Exception;
use Illuminate\Http\Request;
use InvalidArgumentException;



class BoloInteressadoController extends Controller
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
     * Método para listar todos os interessados associados a um bolo específico
     * @return ResponseJson: o retorno pode ser de duas formas:
     *  1-)Array com dados: caso haja dados salvos no banco de dados
     *  2-)Array vazio: caso não haja dados no banco de dados
     */

    public function index($bolo_id)
    {
       return $this->service->indexBoloInteressadosBolo($bolo_id);
    }

     /**
     * Método para registrar no banco uma nova lista de interessados em um bolo específico
     * @param bolo_id      (required|integer) id do bolo no qual contém os associados
     * @param interessados (nullable|array) lista de e-mails dos novos interessados
     * @return ResponseJson: o retorno pode ser de duas formas:
     *  1-)Sucesso: caso os dados sejam salvos corretamente no banco de dados
     *  2-)Error:
     *    a-)Exceção InvalidArgumentException: quando o sistema identifica que um ou mais valores estão incorretos, de acordo com a validação
     *    b-)Exceção Genérica Exception: quando o sistema tenta cadastrar os dados no banco, porém, ocorre algum erro não previsto
     */

    public function store(Request $request,$bolo_id){
        try{
            $this->service->addInteressadosBolo($request->all(),$bolo_id);            
            return response()->json(['msg'=>'E-mails adicionados ao cadastro do bolo com sucesso!','status'=>201],201);
         }catch(InvalidArgumentException $e){
             return response()->json(['msg'=>$e->getMessage(),'status'=>400],400);
         }catch(Exception $e){
             return response()->json(['msg'=>'Não foi possível associar os e-mails ao bolo em questão','status'=>500],500);
         }
    }

     /**
     * Método para buscar um interessado específico associado ao bolo
     * @param bolo_interessado_id (integer) id do interessado
     * @return ResponseJson: o retorno pode ser de duas formas:
     *  1-)Sucesso: os dados do interessado específico
     *  2-)Error:
     *    a-)Exceção Genérica Exception: quando o sistema não encontra o interessado a partir do id enviado
     */

    public function edit($bolo_interessado_id){
        try{
            return $this->service->editBoloInteressado($bolo_interessado_id);
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage(),'status'=>404],404);
        }
    }

      /**
     * Método para atualizar um interessado específico associado a um bolo 
     * @param bolo_interessado_id (integer) id do interessado
     * @param email               (required|string) e-mail a ser atualizado do interessado
     * @return ResponseJson: o retorno pode ser de duas formas:
     *  1-)Sucesso: caso os dados sejam atualizados corretamente no banco de dados
     *  2-)Error:
     *     a-)Exceção InvalidArgumentException: quando o sistema identifica que um ou mais valores estão incorretos, de acordo com a validação
     *     b-)Exceção Genérica Exception: quando o sistema tenta atualizar os dados no banco, porém, ocorre algum erro não previsto
     */

    public function update(Request $request,$bolo_interessado_id){
        try{
            $this->service->updateBoloInteressado($request->all(),$bolo_interessado_id);            
            return response()->json(['msg'=>'Interessado bolo atualizado com sucesso!','status'=>201],201);
         }catch(InvalidArgumentException $e){
             return response()->json(['msg'=>$e->getMessage(),'status'=>400],400);
         }catch(Exception $e){
             return response()->json(['msg'=>'Não foi possível realizar a atualização do interessado no bolo','status'=>500],500);
         }
    }

      /**
     * Método para excluir um interessado específico 
     * @param bolo_interessado_id (integer) id do interessado
     * @return ResponseJson: o retorno pode ser de duas formas:
     *  1-)Sucesso: se os dados forem excluídos corretamente.
     *  2-)Error:
     *    a-)Exceção Genérica Exception: quando o sistema tenta excluir os dados no banco, porém, ocorre algum erro não previsto
     */

    public function destroy($bolo_interessado_id)
    {
        try{
            $this->service->deleteBoloInteressado($bolo_interessado_id);            
            return response()->json(['msg'=>'Interessado associado ao bolo deletado com sucesso!','status'=>201],201);
         }catch(Exception $e){
             return response()->json(['msg'=>'Não foi possível realizar a exclusão do interessado associado ao bolo','status'=>500],500);
         }
    }
}
