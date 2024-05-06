<?php

namespace Internia;

use think\Request;
use think\response\Redirect;

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
        if ($response->getCode() === 200 && empty($response->getContent())) {
            $response = $this->onEmptyResponse($request, $response);
        }
        if (! $request->header('X-Inertia')) {
            return $response;
        }
        return $response;
    }

    public function onEmptyResponse(Request $request, \think\Response $response): Redirect
    {
        return redirect()->restore();
    }

    public function version(Request $request): bool|string|null
    {
        if (config('app.asset_url')) {
            return md5(config('app.asset_url'));
        }

        if (file_exists($manifest = public_path('mix-manifest.json'))) {
            return md5_file($manifest);
        }

        if (file_exists($manifest = public_path('build/manifest.json'))) {
            return md5_file($manifest);
        }

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
        return null;
    }
}