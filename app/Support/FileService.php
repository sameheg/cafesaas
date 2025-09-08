<?php

namespace App\Support;

use App\Models\File as FileModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FileService
{
    public function store(UploadedFile $file, ?string $disk = null): FileModel
    {
        $disk = $disk ?: config('filesystems.default');
        $tenant = app(TenantManager::class)->tenant();
        $directory = $tenant ? "tenant-{$tenant->id}" : 'misc';
        $path = $file->store($directory, $disk);

        $thumbnail = null;
        if (str_starts_with($file->getMimeType(), 'image/')) {
            $thumbnail = $this->makeThumbnail($file, $disk, $directory);
        }

        return FileModel::create([
            'disk' => $disk,
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'thumbnail_path' => $thumbnail,
        ]);
    }

    protected function makeThumbnail(UploadedFile $file, string $disk, string $directory): string
    {
        $manager = new ImageManager(new Driver);
        $image = $manager->read($file->getRealPath())->scaleDown(200, 200);
        $thumbName = Str::beforeLast($file->hashName(), '.').'-thumb.'.$file->extension();
        $thumbPath = $directory.'/'.$thumbName;
        Storage::disk($disk)->put($thumbPath, (string) $image->encode());

        return $thumbPath;
    }

    public function temporaryUrl(FileModel $file, int $minutes = 5): string
    {
        $disk = Storage::disk($file->disk);
        $expiration = now()->addMinutes($minutes);

        $driver = config("filesystems.disks.{$file->disk}.driver");

        if ($driver !== 'local' && method_exists($disk, 'temporaryUrl')) {
            return $disk->temporaryUrl($file->path, $expiration);
        }

        return URL::temporarySignedRoute('files.download', $expiration, ['file' => $file->id]);
    }
}
