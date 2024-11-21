<?php

require_once __DIR__ . '/api/FileStorage.php';

header('Content-Type: application/json');


$uploadDir = __DIR__ . '/uploads/';


$fileStorage = new FileStorage($uploadDir);


$requestMethod = $_SERVER['REQUEST_METHOD'];
$endpoint = explode('/', $_SERVER['REQUEST_URI']);
$action = end($endpoint);


function sendResponse($statusCode, $message, $data = null)
{
    http_response_code($statusCode);
    echo json_encode([
        'status' => $statusCode,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

switch ($requestMethod) {
    case 'POST':

        if ($action === 'upload') {
            $response = $fileStorage->uploadFile($_FILES['fileToUpload']);
            sendResponse($response['status'], $response['message'], ['file' => $response['file'] ?? null]);
        }
        break;

    case 'GET':

        if ($action === 'files') {
            $response = $fileStorage->listFiles();
            echo $response;
        }
        break;

    case 'DELETE':

        if ($action === 'delete' && isset($_GET['file'])) {
            $response = $fileStorage->deleteFile($_GET['file']);

            sendResponse($response['status'], $response['message']);
        }
        break;

    default:

        sendResponse(405, "Method Not Allowed");
        break;
}
