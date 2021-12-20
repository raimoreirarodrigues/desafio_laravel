<?php

namespace Tests\Feature;

use App\Models\Bolo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoloEditTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        //Obter dados para edição de um bolo
        $bolo = Bolo::first();
        if(!is_null($bolo)){
            $this->get('/api/v1/bolo/'.$bolo->id.'/edit')->assertStatus(200);
        } 
    }
}
