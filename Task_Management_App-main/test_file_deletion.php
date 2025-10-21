<?php

/**
 * Test File Deletion Functionality
 * 
 * This script tests:
 * 1. Upload a file via API
 * 2. Verify file exists in database and file system
 * 3. Delete file via API
 * 4. Verify file is removed from database and file system
 */

require_once 'task-app/vendor/autoload.php';

class FileDeleteTest
{
    private $baseUrl = 'http://127.0.0.1:8000/api/v1';
    private $authToken = null;
    private $taskId = null;
    private $attachmentId = null;

    public function run()
    {
        echo "=== FILE DELETION TEST ===\n\n";
        
        try {
            // Step 1: Login to get auth token
            $this->login();
            echo "✓ Login successful\n";
            
            // Step 2: Create a task to attach file to
            $this->createTask();
            echo "✓ Task created (ID: {$this->taskId})\n";
            
            // Step 3: Upload a test file
            $this->uploadTestFile();
            echo "✓ File uploaded (Attachment ID: {$this->attachmentId})\n";
            
            // Step 4: Verify file exists in database
            $this->verifyFileInDatabase();
            echo "✓ File exists in database\n";
            
            // Step 5: Verify file exists in file system
            $filePath = $this->verifyFileInFileSystem();
            echo "✓ File exists in file system: {$filePath}\n";
            
            // Step 6: Delete file via API
            $this->deleteFileViaAPI();
            echo "✓ File deleted via API\n";
            
            // Step 7: Verify file removed from database
            $this->verifyFileRemovedFromDatabase();
            echo "✓ File removed from database\n";
            
            // Step 8: Verify file removed from file system
            $this->verifyFileRemovedFromFileSystem($filePath);
            echo "✓ File removed from file system\n";
            
            echo "\n🎉 ALL TESTS PASSED! File deletion works correctly.\n";
            
        } catch (Exception $e) {
            echo "\n❌ TEST FAILED: " . $e->getMessage() . "\n";
            if (isset($e->response)) {
                echo "Response: " . json_encode($e->response, JSON_PRETTY_PRINT) . "\n";
            }
        }
    }

    private function login()
    {
        $response = $this->makeRequest('POST', '/auth/login', [
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);
        
        if (!$response['success']) {
            throw new Exception('Login failed: ' . $response['message']);
        }
        
        $this->authToken = $response['data']['token'];
    }

    private function createTask()
    {
        $response = $this->makeRequest('POST', '/tasks', [
            'title' => 'Test Task for File Deletion',
            'description' => 'This task is created for testing file deletion functionality',
            'priority' => 'medium',
            'due_date' => date('Y-m-d H:i:s', strtotime('+1 week'))
        ], true);
        
        if (!$response['success']) {
            throw new Exception('Task creation failed: ' . $response['message']);
        }
        
        $this->taskId = $response['data']['task']['id'];
    }

    private function uploadTestFile()
    {
        // Create a temporary test file
        $testContent = "This is a test file for deletion testing.\nCreated at: " . date('Y-m-d H:i:s');
        $tempFile = tempnam(sys_get_temp_dir(), 'test_file_');
        file_put_contents($tempFile, $testContent);
        
        // Upload file using multipart form data
        $boundary = uniqid();
        $data = '';
        
        // Add file
        $data .= "--{$boundary}\r\n";
        $data .= "Content-Disposition: form-data; name=\"files[]\"; filename=\"test-file.txt\"\r\n";
        $data .= "Content-Type: text/plain\r\n\r\n";
        $data .= file_get_contents($tempFile) . "\r\n";
        $data .= "--{$boundary}--\r\n";
        
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => [
                    "Authorization: Bearer {$this->authToken}",
                    "Content-Type: multipart/form-data; boundary={$boundary}",
                    "Content-Length: " . strlen($data)
                ],
                'content' => $data
            ]
        ]);
        
        $response = file_get_contents($this->baseUrl . "/tasks/{$this->taskId}/attachments", false, $context);
        
        // Clean up temp file
        unlink($tempFile);
        
        if ($response === false) {
            throw new Exception('File upload failed');
        }
        
        $responseData = json_decode($response, true);
        if (!$responseData['success']) {
            throw new Exception('File upload failed: ' . $responseData['message']);
        }
        
        $this->attachmentId = $responseData['data']['attachments'][0]['id'];
    }

    private function verifyFileInDatabase()
    {
        // Connect to database and check if attachment exists
        $pdo = $this->getDatabaseConnection();
        
        $stmt = $pdo->prepare("SELECT * FROM task_attachments WHERE id = ? AND task_id = ?");
        $stmt->execute([$this->attachmentId, $this->taskId]);
        $attachment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$attachment) {
            throw new Exception('Attachment not found in database');
        }
        
        return $attachment;
    }

    private function verifyFileInFileSystem()
    {
        $attachment = $this->verifyFileInDatabase();
        $filePath = $attachment['file_path'];
        
        // Check if file exists in Laravel storage
        $fullPath = __DIR__ . '/task-app/storage/app/' . $filePath;
        
        if (!file_exists($fullPath)) {
            throw new Exception("File not found in file system: {$fullPath}");
        }
        
        return $filePath;
    }

    private function deleteFileViaAPI()
    {
        $response = $this->makeRequest('DELETE', "/tasks/{$this->taskId}/attachments/{$this->attachmentId}", [], true);
        
        if (!$response['success']) {
            throw new Exception('File deletion failed: ' . $response['message']);
        }
    }

    private function verifyFileRemovedFromDatabase()
    {
        $pdo = $this->getDatabaseConnection();
        
        $stmt = $pdo->prepare("SELECT * FROM task_attachments WHERE id = ? AND task_id = ?");
        $stmt->execute([$this->attachmentId, $this->taskId]);
        $attachment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($attachment) {
            throw new Exception('Attachment still exists in database after deletion');
        }
    }

    private function verifyFileRemovedFromFileSystem($filePath)
    {
        $fullPath = __DIR__ . '/task-app/storage/app/' . $filePath;
        
        if (file_exists($fullPath)) {
            throw new Exception("File still exists in file system after deletion: {$fullPath}");
        }
    }

    private function makeRequest($method, $endpoint, $data = [], $requireAuth = false)
    {
        $url = $this->baseUrl . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        
        if ($requireAuth && $this->authToken) {
            $headers[] = "Authorization: Bearer {$this->authToken}";
        }
        
        $context = stream_context_create([
            'http' => [
                'method' => $method,
                'header' => implode("\r\n", $headers),
                'content' => json_encode($data)
            ]
        ]);
        
        $response = file_get_contents($url, false, $context);
        
        if ($response === false) {
            $error = error_get_last();
            throw new Exception("API request failed: " . $error['message']);
        }
        
        return json_decode($response, true);
    }

    private function getDatabaseConnection()
    {
        // Load Laravel environment to get database config
        $envPath = __DIR__ . '/task-app/.env';
        if (file_exists($envPath)) {
            $env = file_get_contents($envPath);
            $lines = explode("\n", $env);
            $config = [];
            
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && !str_starts_with(trim($line), '#')) {
                    [$key, $value] = explode('=', $line, 2);
                    $config[trim($key)] = trim($value);
                }
            }
        } else {
            // Default config
            $config = [
                'DB_HOST' => '127.0.0.1',
                'DB_PORT' => '3306',
                'DB_DATABASE' => 'task_management_db',
                'DB_USERNAME' => 'root',
                'DB_PASSWORD' => ''
            ];
        }
        
        $dsn = "mysql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_DATABASE']}";
        return new PDO($dsn, $config['DB_USERNAME'], $config['DB_PASSWORD']);
    }
}

// Run the test
$test = new FileDeleteTest();
$test->run();