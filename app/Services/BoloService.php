<?php

namespace App\Services;

use App\Models\Bolo;
use App\Models\BoloInteressado;
use App\Validators\BoloValidator;
use Exception;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;

class BoloService{

    public function validateBolo($data,$id=null){
        $rules = App::make(BoloValidator::class)->getRules($data,$id);
        $validator =  \Validator::make($data,$rules);
        if ($validator->fails()) { 
            throw new InvalidArgumentException($validator->messages());
        }
    }

    public function create($data){
        try{
            //Valida dados:nome,peso, valor e quantidade
            $this->validateBolo($data);
            DB::beginTransaction();
            $bolo = Bolo::create($data);
            if(isset($data['interessados'])){
               foreach ($data['interessados'] as $interessado_email) {
                 $bolo->interessados()->save(new BoloInteressado(['email'=>$interessado_email]));
               }
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

    public function update($data,$id){
        try{
            //Valida dados:nome,peso, valor e quantidade
            $this->validateBolo($data,$id);
            DB::beginTransaction();
            $bolo = Bolo::findOrFail($id);
            $bolo->update($data);
            $remove_emails = BoloInteressado::where('bolo_id',$bolo->id)->pluck('id')->toArray();
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
}