<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Task;

class DebugController extends Controller
{
    public function debugAuth(Request $request): JsonResponse
    {
        $user = auth()->user();
        $authId = auth()->id();
        
        return response()->json([
            'auth_check' => auth()->check(),
            'auth_id' => $authId,
            'auth_id_type' => gettype($authId),
            'user_object' => $user ? [
                'id' => $user->id,
                'id_type' => gettype($user->id),
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username ?? 'N/A'
            ] : null,
            'request_user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
            ] : null
        ]);
    }
    
    public function debugUpload(Request $request, Task $task): JsonResponse
    {
        try {
            $user = auth()->user();
            $authId = auth()->id();
            
            \Log::info('Debug upload data', [
                'auth_check' => auth()->check(),
                'auth_id' => $authId,
                'auth_id_type' => gettype($authId),
                'auth_user_id' => auth()->user() ? auth()->user()->id : null,
                'auth_user_id_type' => auth()->user() ? gettype(auth()->user()->id) : null,
                'user_id' => $user ? $user->id : null,
                'user_name' => $user ? $user->name : null,
                'task_id' => $task->id
            ]);
            
            if (!$request->hasFile('attachment')) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }
            
            $file = $request->file('attachment');
            
            // Test data that would be inserted
            $testData = [
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => 'test.txt',
                'file_path' => 'test/path',
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension(),
                'uploaded_by_auth_id' => $authId, // This was the problem - returns username
                'uploaded_by_user_id' => auth()->user()->id, // This should work - returns actual ID
            ];
            
            \Log::info('Test data to insert', $testData);
            
            return response()->json([
                'success' => true,
                'test_data' => $testData,
                'auth_debug' => [
                    'auth_check' => auth()->check(),
                    'auth_id' => $authId,
                    'auth_id_type' => gettype($authId),
                    'user_object' => $user
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Debug upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}