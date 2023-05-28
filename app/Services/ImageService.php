<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Throwable;

class ImageService
{
    public function savePublicImage(int $userId, string $folder,  ?bool $resize = false): array
    {
        $image = request('image');
        $originalSizeImage = $image->store($folder, 'temp');
        $imageName = $userId . time() . '.' . $image->extension();

        if ($resize) {
            try {
                $img = Image::make($image->path());
                $img->resize(100, 100);
                $img->encode('png')->save(storage_path('app/public/' . $folder . '/' . $imageName));
            } catch (Throwable $throwable) {
                $this->moveReadableFileInStorage(
                    'temp/' . $originalSizeImage,
                    'public/' . $folder . '/' . $imageName
                );
            }
        } else {
            $this->moveReadableFileInStorage(
                'temp/' . $originalSizeImage,
                'public/' . $folder . '/' . $imageName
            );
        }

        return ['image' => 'storage/' . $folder . '/' . $imageName];
    }

    private function moveReadableFileInStorage(string $fromFilename, string $toFilename): void
    {
        $destinationPath = storage_path('app/' . $toFilename);
        $lastSlashPosition = strrpos($destinationPath, '/');
        $directoryToMoveImage = substr($destinationPath, 0, $lastSlashPosition + 1);

        if (!File::isDirectory($directoryToMoveImage)) {
            File::makeDirectory($directoryToMoveImage);
        }
        File::move(
            storage_path('app/' . $fromFilename),
            $destinationPath,
        );
        File::chmod(storage_path('app/' . $toFilename), 0644);
    }
}
