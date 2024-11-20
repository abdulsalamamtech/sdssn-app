<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogActivities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Get the user's IP address
        $ipAddress = $request->ip();
        // Get the User-Agent string
        $userAgent = $request->header('User-Agent');
        // Get the device, platform, and browser details from the User-Agent string
        $device = $this->detectDevice($userAgent);
        $platform = $this->detectPlatform($userAgent);
        $browser = $this->detectBrowser($userAgent);

        // Prepare the data to be logged
        $data = [
            'request' => $request->all() ?? $request->getContent(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device' => $device,
            'platform' => $platform,
            'browser' => $browser,
        ];

        Log::info('Activities:', $data);

        return $next($request);
    }


    // use Illuminate\Http\Request;

    public function getUserDetails(Request $request)
    {
        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');

        $device = $this->detectDevice($userAgent);
        $platform = $this->detectPlatform($userAgent);
        $browser = $this->detectBrowser($userAgent);

        return response()->json([
            'ip_address' => $ipAddress,
            'device' => $device,
            'platform' => $platform,
            'browser' => $browser,
        ]);
    }

    private function detectDevice($userAgent)
    {
        if (stripos($userAgent, 'iPhone') !== false) {
            return 'iPhone';
        } elseif (stripos($userAgent, 'Android') !== false) {
            return 'Android';
        } elseif (stripos($userAgent, 'Windows') !== false) {
            return 'Windows PC';
        } elseif (stripos($userAgent, 'Macintosh') !== false) {
            return 'Mac';
        } else {
            return 'Unknown Device';
        }
    }

    private function detectPlatform($userAgent)
    {
        if (stripos($userAgent, 'Windows') !== false) {
            return 'Windows';
        } elseif (stripos($userAgent, 'Mac OS') !== false || stripos($userAgent, 'Macintosh') !== false) {
            return 'Mac OS';
        } elseif (stripos($userAgent, 'Linux') !== false) {
            return 'Linux';
        } elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
            return 'iOS';
        } elseif (stripos($userAgent, 'Android') !== false) {
            return 'Android';
        } else {
            return 'Unknown Platform';
        }
    }

    private function detectBrowser($userAgent)
    {
        if (stripos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (stripos($userAgent, 'Safari') !== false && stripos($userAgent, 'Chrome') === false) {
            return 'Safari';
        } elseif (stripos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (stripos($userAgent, 'MSIE') !== false || stripos($userAgent, 'Trident') !== false) {
            return 'Internet Explorer';
        } elseif (stripos($userAgent, 'Edge') !== false) {
            return 'Edge';
        } else {
            return 'Unknown Browser';
        }
    }
}
