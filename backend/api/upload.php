<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../config/database.php';
// require_once '../includes/auth.php';

class VideoUploader {
    private $db;
    private $uploadDir = '../uploads/videos/';
    private $thumbnailDir = '../uploads/thumbnails/';
    private $allowedTypes = [
        'video/mp4' => 'mp4',
        'video/mpeg' => 'mpeg',
        'video/quicktime' => 'mov',
        'video/x-matroska' => 'mkv',
        'video/webm' => 'webm',
        'video/x-ms-wmv' => 'wmv',
        'video/x-flv' => 'flv',
        'video/3gpp' => '3gp',
        'video/x-msvideo' => 'avi',
        'video/mov' => 'mov'
    ];

    public function __construct() {
        $this->db = new Database();
    }

    public function uploadVideo($videoData) {
        $validationResult = $this->validateVideoData($videoData);
        if (!$validationResult['isValid']) {
            return ['success' => false, 'message' => $validationResult['message']];
        }

        // Generate unique filename
        $extension = $this->allowedTypes[$videoData['type']];
        $videoFilename = uniqid() . '.' . $extension;
        $videoPath = $this->uploadDir . $videoFilename;
        $thumbnailPath = $this->generateThumbnail($videoPath);
        return ['success' => true, 'message' => 'video data uploaded'];
        
    }

    private function validateVideoData($videoData) {
        print_r($videoData);
        // Check if file was uploaded
        if (!isset($videoData['tmp_name']) || empty($videoData['tmp_name'])) {
            return ['isValid' => false, 'message' => 'No file was uploaded'];
        }
        
        //Get the actual MIME type of the file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $videoData['tmp_name']);
        finfo_close($finfo);
        
        //check file type
        if(!array_key_exists($mimeType, $allowedTypes)) {
            return [
                'isValid' => false,
                'message' => 'Invalid file type. Allowed types: ' . implode(', ', array_values($this->allowedTypes))
            ];
        }
        $maxFileSize = 500 * 1024 * 1024;  //500MB

        if ($videoData['size'] > $maxFileSize) {
            return [
                'isValid' => false, 
                'message' => 'File too large. Maximum size is 500MB'
            ];
        }

        // Additional checks for file integrity
        if (!is_uploaded_file($videoData['tmp_name'])) {
            return ['isValid' => false, 'message' => 'Invalid upload'];
        }

        return ['isValid' => true, 'message' => 'Validation successful'];

    }
    
    private function generateThumbnail($videoPath) {
        //Use FFmpeg to generate thumbnail
        $thumbnailFilename = uniqid() . '.jpg';
        $thumbnailPath = $this->thumbnailDir . $thumbnailFilename;
        
        //Ffmpeg command to extract thumbnail at 5 seconds
        $command = "ffmpeg -i " . escapeshellarg($videoPath) . " -ss 00:00:05 -vframes 1 " . escapeshellarg($thumbnailPath);
        exec($command, $output, $returnVar);

        return $returnVar == 0 ? $thumbnailPath : null;
    }
}

//Handle Upload Request
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $a = ['success' => true, 'message' => 'video data uploaded'];
    // echo json_encode($a);
    $uploader = new VideoUploader();
    $result = $uploader->uploadVideo($_FILES['video']);
    echo json_encode($result);
}

?>