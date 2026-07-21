<?php

namespace App\Services;

class FileUploadService
{
    public function upload(
        array $file,
        array $allowedMimeTypes = [],
        string $directory='storage/uploads'
    ): string {

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('خطا در آپلود فایل');
        }

        $mimeType = mime_content_type($file['tmp_name']);

        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new \Exception('فرمت فایل مجاز نیست');
        }

        $extension = pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        );

        $fileName = uniqid('file_', true) . '.' . $extension;

        $uploadPath = BASE_PATH . '/' . $directory . '/' . $fileName;

        if (!move_uploaded_file(
            $file['tmp_name'],
            $uploadPath
        )) {
            throw new \Exception('ذخیره فایل با خطا مواجه شد');
        }

        return $fileName;
    }
}