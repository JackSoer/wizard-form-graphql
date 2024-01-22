<?php

namespace App\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadController extends Controller
{
    public static function uploadFile($file)
    {
        $filePath = null;

        if ($file) {
            $filePath = $file->storePublicly('public');
            $filePath = '/storage/' . $filePath;
            $filePath = str_replace('/public', '', $filePath);
        }

        return $filePath;
    }

    public static function deleteFile(string $filePath)
    {
        if (File::exists(public_path($filePath))) {
            File::delete(public_path($filePath));
        } else {
            throw new FileException("File doesn't exist");
        }
    }
}
