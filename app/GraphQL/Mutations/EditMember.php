<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Member;
use App\Utils\UploadController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Illuminate\Support\Str;

final readonly class EditMember
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $args['input'] = $this->transformToSnakeCase($args['input']);

        $validator = Validator::make($args['input'], [
            'first_name' => ['required', 'between:1,30', 'string'],
            'last_name' => ['required', 'between:1,30', 'string'],
            'birthdate' => ['required', 'date_format:Y-m-d', 'before:tomorrow', 'date'],
            'report_subject' => ['required', 'between:1,100', 'string'],
            'country' => ['required', 'string', 'between:2,100'],
            'phone' => ['required', 'string', 'between:10,100'],
            'email' => ['required', 'email:rfc,dns', Rule::unique('members')->ignore($args['id'])],
            'company' => ['nullable', 'string', 'max:40'],
            'position' => ['nullable', 'string', 'max:40'],
            'about_me' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'file', 'max:5242880', 'image'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = "Validation failed for the following fields: " . implode(', ', array_keys($errors));
            throw new ValidationException($errorMessage, $validator);
        }

        $member = Member::findOrFail($args['id']);

        if (isset($args['photo'])) {
            $photo = $args['photo'];
            unset($args['photo']);

            $filePath = null;
            if ($photo !== null) {
                $filePath = UploadController::uploadFile($photo);
            }

            $member->photo = $filePath;
        }

        $member->update($args['input']);

        return $member;
    }

    private function transformToSnakeCase(array $input): array
    {
        $transformed = [];

        foreach ($input as $key => $value) {
            $transformed[Str::snake($key)] = $value;
        }
    
        return $transformed;
    }
}
