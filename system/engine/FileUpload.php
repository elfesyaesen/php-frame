<?php

namespace System\Engine;

class FileUpload
{
    private array $mimeTypes;
    private int $maxFileSize;
    private string $uploadDirectory;
    private array $errors = [];

    public function __construct(array $mimeTypes, int $maxFileSize, string $uploadDirectory)
    {
        $this->mimeTypes = $mimeTypes;
        $this->maxFileSize = $maxFileSize;
        $this->uploadDirectory = rtrim($uploadDirectory, '/') . '/';

        if (!is_dir($this->uploadDirectory)) {
            if (!mkdir($this->uploadDirectory, 0755, true)) {
                throw new RuntimeException("belirttiğiniz dizin bulunamadı.");
            }
        }
    }

    public function upload(array $file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->uploadError($file['error']);
            return null;
        }

        if (!$this->validateFileType($file['type'])) {
            $this->errors[] = "Tanımsız dosya tipi.";
            return null;
        }

        if (!$this->validateFileSize($file['size'])) {
            $this->errors[] = "İzin verilen dosya boyutunu aştınız.";
            return null;
        }

        $fileName = $this->slug($file['name']);
        $destination = $this->uploadDirectory . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->errors[] = "Dosya yükleme dizinine taşınamadı.";
            return null;
        }

        return $fileName;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function validateFileType(string $mimeType): bool
    {
        return in_array($mimeType, $this->mimeTypes, true);
    }

    private function validateFileSize(int $fileSize): bool
    {
        return $fileSize <= $this->maxFileSize;
    }

    private function slug(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $fileName = pathinfo($originalName, PATHINFO_FILENAME);
        $fileName = $this->slugify($fileName);
        return $fileName . '.' . $extension;
    }

    private function slugify(string $text): string
    {
        $text = str_replace(
            ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'],
            ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'],
            $text
        );

        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');

        return $text;
    }

    private function uploadError(int $error_code): string
    {
        return match ($error_code) {
            UPLOAD_ERR_INI_SIZE => "Yüklenen dosya, php.ini dosyasındaki upload_max_filesize direktifini aşıyor.",
            UPLOAD_ERR_FORM_SIZE => "Yüklenen dosya, HTML formunda belirtilen MAX_FILE_SIZE direktifini aşıyor.",
            UPLOAD_ERR_PARTIAL => "Dosya yalnızca kısmen yüklendi.",
            UPLOAD_ERR_NO_FILE => "Hiçbir dosya yüklenmedi.",
            UPLOAD_ERR_NO_TMP_DIR => "Geçici bir klasör bulunamadı.",
            UPLOAD_ERR_CANT_WRITE => "Dosya diske yazılamadı.",
            UPLOAD_ERR_EXTENSION => "Bir PHP eklentisi dosya yüklemesini durdurdu.",
            default => "Bilinmeyen bir yükleme hatası oluştu.",
        };
    }
}
