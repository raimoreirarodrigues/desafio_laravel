<?php

namespace App\Listeners;

use App\Events\DecrementaQuantidadeBoloEvent;
use App\Services\BoloService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use \Illuminate\Support\Facades\Log;

class DecrementaQuantidadeBoloListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $service;

    public function __construct(BoloService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DecrementaQuantidadeBoloEvent $event)
    {
        try{
            $this->service->decrementarBolo($event->bolo_id);
        }catch(\Exception $e){
           Log::debug('Erro ao decrementar bolo. Erro:'.$e->getMessage());
        }
    }
}
