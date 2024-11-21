<?php
class FileStorage
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function uploadFile($file)
    {
        if (isset($file) && $file['error'] === 0) {
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $uniqueFileName = uniqid('', true) . '_' . basename($fileName);
            $fileDestination = $this->uploadDir . $uniqueFileName;

            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                return ['status' => 200, 'message' => "File uploaded successfully", 'file' => $uniqueFileName];
            } else {
                return ['status' => 500, 'message' => "Failed to upload file"];
            }
        } else {
            return ['status' => 400, 'message' => "No file uploaded or upload error"];
        }
    }

    // List all uploaded files
    public function listFiles()
    {
        $files = scandir($this->uploadDir);
        $files = array_diff($files, ['.', '..']);

        return json_encode($files);

        // return ['status' => 200, 'message' => "Files retrieved successfully", 'files' => array_values($files)];
    }

    // Delete a single file
    public function deleteFile($fileName)
    {
        echo "hello";
        $filePath = realpath($this->uploadDir . $fileName);

        // var_dump($filePath);

        // Check if the file exists
        if (file_exists($filePath)) {
            unlink($filePath);
            return ['status' => 200, 'message' => "File deleted successfully"];
        } else {
            return ['status' => 404, 'message' => "File not found"];
        }
    }
}
