<?php

class SecurityMiddleware {
    public static function validateCSRFToken() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
                $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                http_response_code(403);
                die('CSRF token validation failed');
            }
        }
    }

    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateFileUpload($file, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'], $maxSize = 5242880) {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new Exception('Invalid file parameters');
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new Exception('File size exceeds limit');
            case UPLOAD_ERR_PARTIAL:
                throw new Exception('File was only partially uploaded');
            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No file was uploaded');
            case UPLOAD_ERR_NO_TMP_DIR:
                throw new Exception('Missing a temporary folder');
            case UPLOAD_ERR_CANT_WRITE:
                throw new Exception('Failed to write file to disk');
            case UPLOAD_ERR_EXTENSION:
                throw new Exception('A PHP extension stopped the file upload');
            default:
                throw new Exception('Unknown upload error');
        }

        if ($file['size'] > $maxSize) {
            throw new Exception('File size exceeds limit');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception('Invalid file type');
        }

        return true;
    }

    public static function sanitizeFileName($fileName) {
        // Remove any directory components
        $fileName = basename($fileName);
        
        // Remove any non-alphanumeric characters except for dots and hyphens
        $fileName = preg_replace('/[^a-zA-Z0-9.-]/', '', $fileName);
        
        // Add timestamp to prevent filename collisions
        $fileName = time() . '_' . $fileName;
        
        return $fileName;
    }
} 