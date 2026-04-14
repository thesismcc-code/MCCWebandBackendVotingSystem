<?php

namespace App\Http\Middleware;

use App\Application\SystemActivity\RegisterSystemActivity;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogSystemActivity
{
    public function __construct(
        private RegisterSystemActivity $registerSystemActivity
    ) {}

    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if ($this->shouldSkip($request)) {
            return;
        }

        $this->registerSystemActivity->recordHttpActivity($request, $response);
    }

    private function shouldSkip(Request $request): bool
    {
        $path = $request->path();

        return in_array($path, ['up', 'favicon.ico'], true);
    }
}
