<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoloCreateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
       //Cadastro de um bolo com interessados
       $this->postJson('/api/v1/bolo', [
        'nome'=>'Bolo de Milho',
        'peso'=>'500',
        'valor'=>'10.00',
        'quantidade'=>3,
        'interessados'=>['interessado01@dominio.com','interessado02@dominio.com','interessado03@dominio.com']])
        ->assertStatus(201);
    }
}
