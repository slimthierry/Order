<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Employe;

class RatingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_member_id' => $this->user_member_id,
            'rating' => $this->rating,
            'employe_id' => $this->employe_id,
            // 'comment' => $this->comment,
            'average_rating' => $this->employe->ratings->avg('rating'),
            // Casting objects to string, to avoid receive create_at and update_at as object
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
