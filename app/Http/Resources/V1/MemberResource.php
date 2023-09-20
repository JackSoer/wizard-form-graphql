<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'birthdate' => $this->birthdate,
            'reportSubject' => $this->report_subject,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'company' => $this->company,
            'position' => $this->position,
            'aboutMe' => $this->about_me,
            'photo' => $this->photo,
            'isVisible' => $this->is_visible,
        ];
    }
}
