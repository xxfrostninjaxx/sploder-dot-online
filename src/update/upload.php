<?php
require_once '../content/initialize.php';
require(__DIR__.'/../config/env.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('TEMP_DIR', __DIR__ . '/temp/');
define('VERSION_FILE', __DIR__ . '/currentversion.txt');
define('ALLOWED_EXTENSIONS', ['.exe', '.zip']);

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}
if (!is_dir(TEMP_DIR)) {
    mkdir(TEMP_DIR, 0755, true);
}

function sendResponse($success, $message, $data = null) {
    $response = [
        'success' => $success,
        'message' => $message,
        'timestamp' => date('c')
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit();
}

function validateApiKey($key) {
    $validKey = getenv('UPLOAD_API_KEY');
    if (!$validKey) {
        return false;
    }
    return hash_equals($validKey, $key);
}

function updateVersion($version) {
    $version = trim($version);
    if (empty($version)) {
        throw new Exception("Version cannot be empty");
    }
    
    $result = file_put_contents(VERSION_FILE, $version);
    if ($result === false) {
        throw new Exception("Failed to write version file");
    }
    
    return true;
}

function isAllowedFile($filename) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array('.' . $extension, ALLOWED_EXTENSIONS);
}

function cleanupOldFiles() {
    $files = glob(TEMP_DIR . '*');
    $now = time();
    
    foreach ($files as $file) {
        if (is_file($file) && ($now - filemtime($file)) > 86400) {
            unlink($file);
        }
    }
}

function assembleChunks($fileName, $totalChunks, $expectedHash) {
    $tempPrefix = TEMP_DIR . $fileName . '_';
    $finalPath = UPLOAD_DIR . $fileName;
    
    for ($i = 0; $i < $totalChunks; $i++) {
        $chunkPath = $tempPrefix . $i;
        if (!file_exists($chunkPath)) {
            throw new Exception("Missing chunk $i");
        }
    }
    
    $finalFile = fopen($finalPath, 'wb');
    if (!$finalFile) {
        throw new Exception("Cannot create final file");
    }
    
    $totalBytesWritten = 0;
    $bufferSize = 8192;
    
    for ($i = 0; $i < $totalChunks; $i++) {
        $chunkPath = $tempPrefix . $i;
        $chunkFile = fopen($chunkPath, 'rb');
        if (!$chunkFile) {
            fclose($finalFile);
            throw new Exception("Cannot open chunk $i");
        }
        
        $chunkBytesWritten = 0;
        while (!feof($chunkFile)) {
            $buffer = fread($chunkFile, $bufferSize);
            if ($buffer === false) {
                fclose($chunkFile);
                fclose($finalFile);
                throw new Exception("Failed to read chunk $i");
            }
            
            $written = fwrite($finalFile, $buffer);
            if ($written === false) {
                fclose($chunkFile);
                fclose($finalFile);
                throw new Exception("Failed to write chunk $i to final file");
            }
            
            $chunkBytesWritten += $written;
            $totalBytesWritten += $written;
        }
        
        fclose($chunkFile);
        unlink($chunkPath);
    }
    
    fclose($finalFile);
    
    $hashContext = hash_init('md5');
    $finalFile = fopen($finalPath, 'rb');
    
    if (!$finalFile) {
        throw new Exception("Cannot verify file hash - unable to reopen file");
    }
    
    while (!feof($finalFile)) {
        $buffer = fread($finalFile, $bufferSize);
        if ($buffer !== false) {
            hash_update($hashContext, $buffer);
        }
    }
    fclose($finalFile);
    
    $actualHash = hash_final($hashContext);
    
    if ($actualHash !== $expectedHash) {
        unlink($finalPath);
        throw new Exception("File hash mismatch. Expected: $expectedHash, Got: $actualHash");
    }
    
    return $finalPath;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        sendResponse(false, 'Method not allowed');
    }
    
    cleanupOldFiles();
    
    $apiKey = $_POST['api_key'] ?? null;
    $requestType = $_POST['request_type'] ?? 'upload';
    
    // Validate API key first
    if (!validateApiKey($apiKey)) {
        http_response_code(401);
        sendResponse(false, 'Invalid API key');
    }
    
    // Handle version update request
    if ($requestType === 'update_version') {
        $version = $_POST['version'] ?? null;
        
        if ($version === null || $version === '') {
            sendResponse(false, 'Missing version number');
        }
        
        try {
            updateVersion($version);
            sendResponse(true, 'Version updated successfully', [
                'version' => $version,
                'version_file' => VERSION_FILE
            ]);
        } catch (Exception $e) {
            sendResponse(false, 'Failed to update version: ' . $e->getMessage());
        }
    }
    
    // Handle file upload request (existing logic)
    $fileName = $_POST['file_name'] ?? null;
    $chunkIndex = $_POST['chunk_index'] ?? null;
    $totalChunks = $_POST['total_chunks'] ?? null;
    $fileHash = $_POST['file_hash'] ?? null;

    if (!isset($_FILES['chunk_data'])) {
        sendResponse(false, 'Chunk data not found in upload');
    }
    
    if ($_FILES['chunk_data']['error'] !== UPLOAD_ERR_OK) {
        $error = $_FILES['chunk_data']['error'];
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        
        $errorMsg = $errorMessages[$error] ?? "Unknown upload error ($error)";
        sendResponse(false, "Chunk data upload failed: $errorMsg");
    }
    
    $tmpFile = $_FILES['chunk_data']['tmp_name'];
    $uploadedSize = $_FILES['chunk_data']['size'];

    $requiredFields = [
        'api_key' => $apiKey,
        'file_name' => $fileName, 
        'chunk_index' => $chunkIndex,
        'total_chunks' => $totalChunks,
        'file_hash' => $fileHash
    ];

    foreach ($requiredFields as $field => $value) {
        if ($value === null || $value === '') {
            sendResponse(false, "Missing required field: $field");
        }
    }

    $fileName = basename($fileName);
    $chunkIndex = (int)$chunkIndex;
    $totalChunks = (int)$totalChunks;
    
    if (!isAllowedFile($fileName)) {
        sendResponse(false, 'File type not allowed');
    }

    if ($uploadedSize > 90 * 1024 * 1024) {
        sendResponse(false, 'Chunk size too large');
    }
    
    $chunkPath = TEMP_DIR . $fileName . '_' . $chunkIndex;
    
    if (!move_uploaded_file($tmpFile, $chunkPath)) {
        sendResponse(false, 'Failed to save chunk');
    }
    
    if ($chunkIndex === $totalChunks - 1) {
        try {
            $finalPath = assembleChunks($fileName, $totalChunks, $fileHash);
            $fileSize = filesize($finalPath);
            
            sendResponse(true, 'File uploaded and assembled successfully', [
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'file_path' => $finalPath,
                'chunks_processed' => $totalChunks
            ]);
        } catch (Exception $e) {
            sendResponse(false, 'Assembly failed: ' . $e->getMessage());
        }
    } else {
        sendResponse(true, "Chunk $chunkIndex received successfully", [
            'chunk_index' => $chunkIndex,
            'chunks_remaining' => $totalChunks - $chunkIndex - 1
        ]);
    }
    
} catch (Throwable $e) {
    if (!headers_sent()) {
        http_response_code(500);
    }
    sendResponse(false, 'Internal server error: ' . $e->getMessage());
}
?>
