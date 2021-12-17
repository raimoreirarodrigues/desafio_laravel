<?php

namespace App\Jobs;

use App\Events\DecrementaQuantidadeBoloEvent;
use App\Mail\SendMailNotificarInteressadoBolo;
use App\Models\BoloInteressado;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use \Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificarInteressadoBoloJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $tries = 4;
    private $interessado_bolo_id;

    public function __construct($interessado_bolo_id)
    {
        $this->interessado_bolo_id = $interessado_bolo_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $interessado = BoloInteressado::find($this->interessado_bolo_id);
            //Verifica novamente se existe quantidade para envio de notificação
            if($interessado->bolo->quantidade>0){
                Log::info('Enviando notificação para: '.$interessado->email);
                Mail::to($interessado->email)->send(new SendMailNotificarInteressadoBolo($interessado->bolo->nome));
                $interessado->notificado = true;
                $interessado->save();
                //Diminui a quantidade de bolo disponível
                event(new DecrementaQuantidadeBoloEvent($interessado->bolo_id));
            }
            
        }catch(Exception $e){
           if($this->attempts() <= 3){
             $this->release(120);
             return;
           }else{
             Log::error('Falha ao enviar notificação interessado bolo. (id interessado: '.$this->interessado_bolo_id.')'. 'Erro: '.$e->getMessage());
           }
        }
    }
}
