<?php

namespace App\Http\Resources\V1;

use App\Utils\FieldFormatter;
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
        $fields = ['id', 'first_name', 'last_name', 'birthdate', 'report_subject', 'country', 'phone', 'email', 'company', 'position', 'about_me', 'photo', 'is_visible'];

        return FieldFormatter::formatFieldsToCamelCase($this, $fields);
    }
}
