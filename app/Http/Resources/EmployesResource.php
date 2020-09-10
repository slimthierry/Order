<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Employe;
use App\Models\UserMember;

class EmployesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
        [
            'id' => $this->id,
            "firstname" => $this->firstname,
            "lastname"=>$this->lastname,
            "phonenumber"=>$this->phonenumber,
            "identity"=>$this->identity,
            "address"=>$this->address,
            // Casting objects to string, to avoid receive create_at and update_at as object
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
        // parent::toArray($request);
    }
}
