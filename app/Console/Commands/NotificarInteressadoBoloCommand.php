<?php

namespace App\Console\Commands;

use App\Services\BoloService;
use Illuminate\Console\Command;
use \Illuminate\Support\Facades\Log;

class NotificarInteressadoBoloCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificarinteressados:bolo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica lista de interessados dos bolos e notifica via e-mail caso haja bolo disponível';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(BoloService $service)
    {
        try{
            $service->notificarInteressados();
         }catch(\Exception $e){
             Log::error("Erro ao notificar lista de interessados via e-mail: ".$e->getMessage());
         }   
    }
}
