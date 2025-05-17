<?php
// Set headers to allow CORS (adjust origin as needed)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data && isset($data['action']) && $data['action'] === 'errorlogger') {
        // Prepare log data
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $data['action'], // Include action in log
            'error' => [
                'message' => $data['message'] ?? 'Unknown error',
                'source' => $data['source'] ?? 'Unknown',
                'line' => $data['line'] ?? 0,
                'column' => $data['column'] ?? 0,
                'stack' => $data['stack'] ?? 'No stack trace', // Fixed typo in stack
                'userAgent' => $data['userAgent'] ?? 'Unknown',
                'url' => $data['url'] ?? 'Unknown'
            ]
        ];

        // Log to file (you can modify this to log to database instead)
        $logFile = 'error_log.json';
        $logData = json_encode($logEntry, JSON_PRETTY_PRINT) . PHP_EOL;
        file_put_contents($logFile, $logData, FILE_APPEND | LOCK_EX);

        // Send success response
        http_response_code(200);
        echo json_encode(['status' => 'success']);
    } else {
        // Send error response for invalid JSON or missing/invalid action
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data or missing action']);
    }
} else {
    // Send error response for invalid request method
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>