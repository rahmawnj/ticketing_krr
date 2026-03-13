<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiteActive
{
    /**
     * Allowlist path patterns that can be accessed even when the site is suspended.
     */
    private const EXCEPT_PATHS = [
        'secret-setting',
        'secret-setting/*',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $isSuspended = (int) Setting::valueOf('site_suspended', 0) === 1;
        if (!$isSuspended) {
            return $next($request);
        }

        foreach (self::EXCEPT_PATHS as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        $appName = (string) Setting::valueOf('name', config('app.name', 'Website'));

        return response()->view('suspended', [
            'appName' => $appName,
        ], 503);
    }
}
