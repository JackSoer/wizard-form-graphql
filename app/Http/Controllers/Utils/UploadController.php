<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadController extends Controller
{
    public static function uploadFile(string $fileFieldFromRequest)
    {
        $filePath = null;
        $file = request()->file($fileFieldFromRequest);

        if ($file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $filePath = '/storage/' . $filePath;
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
