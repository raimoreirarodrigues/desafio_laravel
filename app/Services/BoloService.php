<?php

namespace App\Services;

use App\Models\BoloInteressado;
use App\Validators\BoloValidator;
use Exception;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;

class BoloService{

    public function validateBolo($bolo){
        $rules = App::make(BoloValidator::class)->getRules($bolo);
        $validator =  \Validator::make($bolo,$rules);
        if ($validator->fails()) { 
            throw new InvalidArgumentException($validator->messages());
        }
    }

    public function create($data){
        try{
            DB::beginTransaction();
            $bolo = BoloInteressado::create($data);
            if(isset($data['emails'])){
                foreach ($data['emails'] as $email) {
                $bolo->interessados()->save(new BoloInteressado(['email'=>$email]));
                //Colocar na fila para notificação via e-mail
                }
            }
            DB::commit();
        }catch(Exception $e){
           DB::rollBack();
           Log::error($e->getMessage());
           throw new Exception($e->getMessage());
        }
    }
}