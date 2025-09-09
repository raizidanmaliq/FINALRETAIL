<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageHelpers
{
    protected $folderPath;

    public function __construct(string $folderPath)
    {
        $this->folderPath = $folderPath;
    }

    public function uploadFile(UploadedFile $file): ?string
    {
        try {
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileExtension = $file->getClientOriginalExtension();
            $fileNameToStore = Str::slug($fileName) . '-' . time() . '.' . $fileExtension;
            $path = $file->move($this->folderPath, $fileNameToStore);
            return $path;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function uploadImage(Request $request, $typeRequest)
    {
        if ($request->hasFile($typeRequest)) {
            $fileNameWithExt = $request->file($typeRequest)->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $fileExtension = $request->file($typeRequest)->getClientOriginalExtension();
            $fileNameToStore = preg_replace('/\s+/', '-', $fileName) . '-' . time() . '.' . $fileExtension;
            $path = $request->file($typeRequest)->move($this->folderPath, $fileNameToStore);
            if ($fileNameToStore != null) {
                return $path;
            } else {
                return "noimage.png";
            }
        } else {
            return "noimage.png";
        }
    }

    public function updateImage(Request $request, $typeRequest, $oldImage)
    {
        if ($request->hasFile($typeRequest)) {
            if ($oldImage != 'noimage.png') {
                File::delete($oldImage);
            }
            $fileNameWithExt = $request->file($typeRequest)->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $fileExtension = $request->file($typeRequest)->getClientOriginalExtension();
            $fileNameToStore = preg_replace('/\s+/', '-', $fileName) . '-' . time() . '.' . $fileExtension;
            $path = $request->file($typeRequest)->move($this->folderPath, $fileNameToStore);
            if ($fileNameToStore != null) {
                return $path;
            } else {
                return $oldImage;
            }
        } else {
            return $oldImage;
        }
    }

    public function deleteImage($image)
    {
        if ($image != 'noimage.png') {
            File::delete($image);
        }
    }
}
