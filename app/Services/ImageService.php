<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function __construct(
        protected ImageManager $imageManager = new ImageManager(new Driver())
    ) {}

    /**
     * Simpan gambar yang di-upload
     */
    public function storeImage(
        $file,          // File dari request
        string $directory, // Direktori penyimpanan
        ?int $width,    // Lebar gambar (opsional)
        callable $callback // Fungsi untuk menyimpan path ke database
    ): void {
        // Generate nama file unik
        $filename = Str::random(40) . '.' . $file->extension();
        $path = "$directory/$filename";

        // Pastikan direktori tersedia
        $this->ensureDirectoryExists($directory);

        // Proses dan simpan gambar
        $this->processAndStoreImage($file, $path, $width);

        // Eksekusi callback (misal: simpan path ke database)
        $callback($path);
    }

    /**
     * Hapus gambar dari storage
     */
    public function deleteImage(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    /**
     * Buat direktori jika belum ada
     */
    protected function ensureDirectoryExists(string $directory): void
    {
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
    }

    /**
     * Resize dan simpan gambar
     */
    protected function processAndStoreImage($file, string $path, ?int $width): void
    {
        $image = $this->imageManager->read($file->getRealPath());

        // Resize jika width diberikan
        if ($width) {
            $image->scale(width: $width);
        }

        // Simpan ke storage
        Storage::disk('public')->put($path, (string) $image->encode());
    }
}