<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Document extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'company' => $this->company,
            'inOrOut' => $this->InOrOut,
            'counterparty' => $this->counterparty,
            'dateOfDoc' => $this->dateOfDoc,
            'typeOfDoc' => $this->typeOfDoc,
            'numberOnDoc' => $this->numberOnDoc,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'shortDesc' => str_limit($this->description, 15),

        ];
    }
}
