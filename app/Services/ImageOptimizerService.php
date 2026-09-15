<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizerService
{
    /**
     * Optimiza, reescala y guarda una imagen en formato WebP con respaldo PNG/JPG
     *
     * @param UploadedFile $file Archivo recibido del request
     * @param string $folder Carpeta dentro de storage/app/public (ej: 'BANNERS', 'MODELOS/1')
     * @param int $maxDimension Dimensión máxima en píxeles (ancho o alto)
     * @param int $quality Calidad de compresión (default: 82)
     * @param string $prefix Prefijo para el archivo generado
     * @return string Ruta relativa guardada (ej: 'BANNERS/IMG_abc123.webp')
     */
    public static function storeOptimized(
        UploadedFile $file,
        string $folder,
        int $maxDimension = 1200,
        int $quality = 82,
        string $prefix = ''
    ): string {
        $folder = trim($folder, '/');
        $random = Str::random(10);
        $baseName = $prefix !== '' ? "{$prefix}_{$random}" : $random;
        $extension = strtolower($file->getClientOriginalExtension());

        // Si no es imagen estándar o GD no está disponible, guardar directo
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp']) || !extension_loaded('gd')) {
            $fileName = "{$baseName}.{$extension}";
            Storage::disk('public')->putFileAs($folder, $file, $fileName);
            return "{$folder}/{$fileName}";
        }

        $imageContent = file_get_contents($file->getRealPath());
        $image = @imagecreatefromstring($imageContent);

        if (!$image) {
            $fileName = "{$baseName}.{$extension}";
            Storage::disk('public')->putFileAs($folder, $file, $fileName);
            return "{$folder}/{$fileName}";
        }

        // Reescalar proporcionalmente si supera la dimensión máxima
        $w = imagesx($image);
        $h = imagesy($image);

        if (max($w, $h) > $maxDimension) {
            $scale = $maxDimension / max($w, $h);
            $newW = (int)round($w * $scale);
            $newH = (int)round($h * $scale);

            $resized = imagecreatetruecolor($newW, $newH);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newW, $newH, $w, $h);
            imagedestroy($image);
            $image = $resized;
        }

        $webpSupported = (imagetypes() & IMG_WEBP);

        if ($webpSupported) {
            $fileName = "{$baseName}.webp";
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);

            ob_start();
            imagewebp($image, null, $quality);
            $output = ob_get_clean();

            Storage::disk('public')->put("{$folder}/{$fileName}", $output);

            // También guardar versión PNG/JPG redimensionada como fallback seguro
            $fallbackExt = ($extension === 'png') ? 'png' : 'jpg';
            $fallbackName = "{$baseName}.{$fallbackExt}";
            ob_start();
            if ($fallbackExt === 'png') {
                imagealphablending($image, false);
                imagesavealpha($image, true);
                imagepng($image, null, 8);
            } else {
                imagejpeg($image, null, $quality);
            }
            $fallbackOutput = ob_get_clean();
            Storage::disk('public')->put("{$folder}/{$fallbackName}", $fallbackOutput);
        } else {
            $fileName = "{$baseName}.{$extension}";
            ob_start();
            if ($extension === 'png') {
                imagealphablending($image, false);
                imagesavealpha($image, true);
                imagepng($image, null, 8);
            } else {
                imagejpeg($image, null, $quality);
            }
            $output = ob_get_clean();
            Storage::disk('public')->put("{$folder}/{$fileName}", $output);
        }

        imagedestroy($image);

        return "{$folder}/{$fileName}";
    }

    /**
     * Elimina el archivo y su contraparte WebP/PNG si existe
     *
     * @param string|null $path
     * @return void
     */
    public static function deleteIfExists(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $clean = ltrim($path, '/');
        $clean = preg_replace('#^(public/|storage/)#', '', $clean);

        Storage::disk('public')->delete($clean);

        // Limpieza de respaldo directo en public_path por si existen archivos previos a symlink
        if (file_exists(public_path('storage/' . $clean))) {
            @unlink(public_path('storage/' . $clean));
        }
        if (file_exists(public_path($path))) {
            @unlink(public_path($path));
        }

        $ext = pathinfo($clean, PATHINFO_EXTENSION);
        if ($ext === 'webp') {
            $png = preg_replace('/\.webp$/i', '.png', $clean);
            $jpg = preg_replace('/\.webp$/i', '.jpg', $clean);
            Storage::disk('public')->delete($png);
            Storage::disk('public')->delete($jpg);
            @unlink(public_path('storage/' . $png));
            @unlink(public_path('storage/' . $jpg));
        } else {
            $webp = preg_replace('/\.(png|jpe?g)$/i', '.webp', $clean);
            Storage::disk('public')->delete($webp);
            @unlink(public_path('storage/' . $webp));
        }
    }
}
