<?php

namespace App\Services;

use App\Http\Resources\BoloCollection;
use App\Http\Resources\BoloInteressadoCollection;
use App\Http\Resources\BoloInteressadoResource;
use App\Http\Resources\BoloResource;
use App\Jobs\NotificarInteressadoBoloJob;
use App\Models\Bolo;
use App\Models\BoloInteressado;
use App\Validators\BoloInteressadoValidator;
use App\Validators\BoloValidator;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;

class BoloService{

    public function indexBolo(){
        return  new BoloCollection(Bolo::get());
    }

    public function create($data){
        try{
            //Valida dados:nome,peso, valor e quantidade
            $this->validateBolo($data);
            DB::beginTransaction();
            $bolo = Bolo::create($data);
            if(isset($data['interessados'])){
               $this->associarEmailsBolo($bolo,$data['interessados']);
            }
            DB::commit();
        }catch(InvalidArgumentException $e){
            throw new InvalidArgumentException($e->getMessage());
         }catch(Exception $e){
           DB::rollBack();
           Log::error($e->getMessage());
           throw new Exception($e->getMessage());
        }
    }

    public function editBolo($id){
        try{
            return new BoloResource(Bolo::findOrFail($id));
        }catch(Exception $e){
            throw new Exception('Nenhum bolo encontrado');
        }
    }

    public function update($data,$id){
        try{
            //Valida dados:nome,peso, valor e quantidade
            $this->validateBolo($data,$id);
            DB::beginTransaction();
            $bolo = Bolo::findOrFail($id);
            $bolo->update($data);
            $remove_emails = BoloInteressado::where('bolo_id',$bolo->id)->limit(500)->pluck('id')->toArray();
            if(isset($data['interessados'])){
                foreach ($data['interessados'] as $interessado_email) {
                   $bolo_interessado = BoloInteressado::where('email',$interessado_email)->where('bolo_id',$bolo->id)->first();
                   //Se existir e-mail associado ao bolo, atualiza-o. Caso contrário, cria-se um novo registro
                   if(!is_null($bolo_interessado)){
                       $bolo_interessado->email = $interessado_email;
                       $bolo_interessado->update();
                       $key = array_search($bolo_interessado->id, $remove_emails);
                       if($key!==false){
                           unset($remove_emails[$key]);
                       }
                   }else{
                       $bolo->interessados()->save(new BoloInteressado(['email'=>$interessado_email]));
                   }
                }
            }
            //Exclui todos os e-mails associados ao bolo não enviados na atualização
            BoloInteressado::whereIn('id',$remove_emails)->delete();
            DB::commit();
        }catch(InvalidArgumentException $e){
            throw new InvalidArgumentException($e->getMessage());
         }catch(Exception $e){
           DB::rollBack();
           Log::error($e->getMessage());
           throw new Exception($e->getMessage());
        }
    }

    public function delete($id){
        try{
            DB::beginTransaction();
            Bolo::findOrFail($id)->delete();
            BoloInteressado::where('bolo_id',$id)->delete();
            DB::commit();
        }catch(Exception $e){
           DB::rollBack();
           Log::error($e->getMessage());
           throw new Exception($e->getMessage());
        }
    }

    public function indexBoloInteressadosBolo($bolo_id){
        return new BoloInteressadoCollection(BoloInteressado::where('bolo_id',$bolo_id)->get());
    }

    public function addInteressadosBolo($data,$bolo_id){
        try{
            //Valida dados:nome,peso, valor e quantidade
            $this->validateAssociarListaBolo($data);
            DB::beginTransaction();
            $bolo = Bolo::findOrFail($bolo_id);
            if(isset($data['interessados'])){
              $this->associarEmailsBolo($bolo,$data['interessados']);
            }
            DB::commit();
        }catch(InvalidArgumentException $e){
            throw new InvalidArgumentException($e->getMessage());
         }catch(Exception $e){
           DB::rollBack();
           Log::error($e->getMessage());
           throw new Exception($e->getMessage());
        }
    }

    public function editBoloInteressado($bolo_interessado_id){
        try{
           return new BoloInteressadoResource(BoloInteressado::findOrFail($bolo_interessado_id));
        }catch(Exception $e){
           throw new Exception('Interessado associado ao bolo não encontrado');
        }
    }

    public function updateBoloInteressado($data,$bolo_interessado_id){
        try{
            DB::beginTransaction();
            $bolo_interessado = BoloInteressado::findOrFail($bolo_interessado_id);
            $bolo_interessado->update($data);
            DB::commit();
        }catch(InvalidArgumentException $e){
            throw new InvalidArgumentException($e->getMessage());
         }catch(Exception $e){
           DB::rollBack();
           Log::error($e->getMessage());
           throw new Exception($e->getMessage());
        }
    }

    public function deleteBoloInteressado($bolo_interessado_id){
        try{
            DB::beginTransaction();
            BoloInteressado::findOrFail($bolo_interessado_id)->delete();
            DB::commit();
        }catch(Exception $e){
           DB::rollBack();
           Log::error($e->getMessage());
           throw new Exception($e->getMessage());
        }
    }
    
    //Disparado pelo Evento DecrementaQuantidadeBoloEvent
    public function notificarInteressados(){
        //Busca bolos onde a quantidade > 0
        $bolos_disponiveis = Bolo::where('quantidade','>',0)->pluck('id');
        //Busca por interessados que ainda não foram notificados
        $interessados_nao_notificados = BoloInteressado::whereIn('bolo_id',$bolos_disponiveis)->where('notificado','0')->get();
        foreach($interessados_nao_notificados as $interessado){
            //Cria uma fila para disparo de e-mails aos interessados
            NotificarInteressadoBoloJob::dispatch($interessado->id)->onQueue('notificar_interessado_bolo')->delay(Carbon::now()->addSeconds(10));
        }
    }

    public function decrementarBolo($bolo_id){
        try{
            DB::beginTransaction();
            $bolo = Bolo::find($bolo_id);
            $bolo->quantidade = $bolo->quantidade-1;
            $bolo->save();
            DB::commit();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    private function associarEmailsBolo($bolo,$emails){
        foreach ($emails as $interessado_email) {
            $bolo->interessados()->save(new BoloInteressado(['email'=>$interessado_email]));
          }
    }

    //Validações - Bolo

    public function validateBolo($data,$id=null){
        $rules = App::make(BoloValidator::class)->getRules($data,$id);
        $validator =  \Validator::make($data,$rules);
        if ($validator->fails()) { 
            throw new InvalidArgumentException($validator->messages());
        }
    }

     //Validações - Bolo / Interessados

    public function validateAssociarListaBolo($data,$id=null){
        $rules = App::make(BoloInteressadoValidator::class)->getRules($data,$id);
        $validator =  \Validator::make($data,$rules);
        if ($validator->fails()) { 
            throw new InvalidArgumentException($validator->messages());
        }
    }
}