<?php

namespace Internia;

use think\Request;

class Middleware
{
    private string $rootView = 'app';

    public function handle(Request $request, \Closure $next): \think\Response
    {
        Inertia::version(function() use($request) {
           return $this->version($request); 
        });
        
        Inertia::share($this->share($request));
        Inertia::setRootView($this->rootView($request));

        /** @var \think\Response $response */
        $response = $next($request);

        $response->header([
            'Vary' => true,
            'X-Inertia' => true,
        ]);
        if (! $request->header('X-Inertia')) {
            return $response;
        }
        return $response;
    }

    public function version(Request $request)
    {
        return null;
    }

    public function rootView(Request $request): string
    {
        return $this->rootView;
    }

    public function share(Request $request): array
    {
        return [
            'errors' => function() use($request) {
                return $this->resolveValidationErrors($request);
            }
        ];
    }

    public function resolveValidationErrors(Request $request)
    {
    }
}