<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecureDownloadMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $ip = $request->ip();
        
        // Rate limiting: Limit downloads per user per minute
        $userDownloadKey = 'download_limit_user_' . $user->id;
        $ipDownloadKey = 'download_limit_ip_' . $ip;
        
        $userDownloads = Cache::get($userDownloadKey, 0);
        $ipDownloads = Cache::get($ipDownloadKey, 0);
        
        // Limits: 50 downloads per user per minute, 100 per IP per minute
        $userLimit = 50;
        $ipLimit = 100;
        
        if ($userDownloads >= $userLimit) {
            Log::warning('Download rate limit exceeded for user', [
                'user_id' => $user->id,
                'ip' => $ip,
                'downloads' => $userDownloads
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Too many downloads. Please try again later.'
            ], 429);
        }
        
        if ($ipDownloads >= $ipLimit) {
            Log::warning('Download rate limit exceeded for IP', [
                'user_id' => $user->id,
                'ip' => $ip,
                'downloads' => $ipDownloads
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Too many downloads from this IP. Please try again later.'
            ], 429);
        }
        
        // Check for suspicious activity patterns
        $this->checkSuspiciousActivity($user, $ip, $request);
        
        // Increment counters
        Cache::put($userDownloadKey, $userDownloads + 1, now()->addMinute());
        Cache::put($ipDownloadKey, $ipDownloads + 1, now()->addMinute());
        
        return $next($request);
    }
    
    /**
     * Check for suspicious download activity
     */
    private function checkSuspiciousActivity($user, $ip, Request $request): void
    {
        $userAgent = $request->userAgent();
        
        // Check for automated requests (common bot user agents)
        $suspiciousAgents = [
            'curl', 'wget', 'python', 'requests', 'http', 'bot', 'crawler', 'spider'
        ];
        
        foreach ($suspiciousAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                Log::warning('Suspicious download attempt detected', [
                    'user_id' => $user->id,
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                    'url' => $request->url()
                ]);
                break;
            }
        }
        
        // Check for rapid sequential downloads (more than 10 in 10 seconds)
        $rapidDownloadKey = 'rapid_download_' . $user->id;
        $recentDownloads = Cache::get($rapidDownloadKey, []);
        
        // Clean old timestamps (older than 10 seconds)
        $cutoff = now()->subSeconds(10)->timestamp;
        $recentDownloads = array_filter($recentDownloads, function($timestamp) use ($cutoff) {
            return $timestamp >= $cutoff;
        });
        
        // Add current timestamp
        $recentDownloads[] = now()->timestamp;
        
        if (count($recentDownloads) > 10) {
            Log::warning('Rapid download activity detected', [
                'user_id' => $user->id,
                'ip' => $ip,
                'downloads_in_10s' => count($recentDownloads)
            ]);
        }
        
        // Store updated recent downloads
        Cache::put($rapidDownloadKey, $recentDownloads, now()->addMinute());
    }
}