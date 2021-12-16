<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BoloResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'peso' => $this->peso,
            'quantidade' => $this->quantidade,
            'interessados'=>BoloInteressadoResource::collection($this->interessados),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
