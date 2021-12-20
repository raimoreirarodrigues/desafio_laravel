<?php

namespace Tests\Feature;

use App\Models\Bolo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoloDeleteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        //Delete um bolo com seus interessados
        $bolo = Bolo::first();
        if(!is_null($bolo)){
           $this->deleteJson('/api/v1/bolo/'.$bolo->id)->assertStatus(200);
        }
    }
}
