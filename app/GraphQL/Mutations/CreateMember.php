<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Member;
use App\Utils\UploadController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

final readonly class CreateMember
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
            'email' => ['required', 'email:rfc,dns', 'unique:members'],
            'company' => ['nullable', 'string', 'max:40'],
            'position' => ['nullable', 'string', 'max:40'],
            'about_me' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'file', 'max:5242880', 'image'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        if(isset($args['input']['photo'])) {

            $filePath = UploadController::uploadFile($args['input']['photo']);
            $args['input']['photo'] = $filePath;
        }

        return Member::create($args['input']);
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
