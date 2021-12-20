<?php

namespace Tests\Feature;

use App\Models\Bolo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoloUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        //Atualiza de um bolo com interessados
        $bolo = Bolo::first();
        if(!is_null($bolo)){
           $this->putJson('/api/v1/bolo/'.$bolo->id, [
              'nome'=>'Bolo de Chocolate',
              'peso'=>'600',
              'valor'=>'15.00',
              'quantidade'=>5,
              'interessados'=>['interessado04@dominio.com','interessado05@dominio.com','interessado06@dominio.com']])
              ->assertStatus(201);
        }
        
    }
}

