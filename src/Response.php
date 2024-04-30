<?php

namespace Internia;

use think\facade\View;
use think\Request;
use think\response\Json;

class Response
{
    protected string $component;

    protected array $props;

    protected string $rootView;

    protected string $version;

    protected array $viewData = [];

    const HeaderPrefix = "X-Inertia";
    const HeaderVersion =  self::HeaderPrefix . "-Version";
    const HeaderLocation = self::HeaderPrefix . "-Location";
    const HeaderPartialData = self::HeaderPrefix . "-Partial-Data";
    private int $code;


    public function __construct(string $component, array $props, string $rootView = 'app', string $version)
    {
        $this->component = $component;
        $this->props = $props;
        $this->rootView = $rootView;
        $this->version = $version;
    }

    public function with($key, $value = null): self
    {
        if (is_array($key)) {
            $this->props = array_merge($this->props, $key);
        } else {
            $this->props[$key] = $value;
        }

        return $this;
    }

    public function withViewData($key, $value = null): self
    {
        if (is_array($key)) {
            $this->viewData = array_merge($this->viewData, $key);
        } else {
            $this->viewData[$key] = $value;
        }

        return $this;
    }

    public function rootView(string $rootView): self
    {
        $this->rootView = $rootView;

        return $this;
    }

    public function toResponse(Request $request): string|\think\Response
    {
        $only = array_filter(explode(',', $request->header(self::HeaderPartialData, '')));
        if ($only && $request->header(self::HeaderPartialData) == $this->component) {
            $props = $only;
        } else {
            $props = $this->props;
        }

        $page = [
            'component' => $this->component,
            'props' => $props,
            'url' => $request->url(true),
            'version' => $this->version,
        ];

        if ($request->header(self::HeaderPrefix)) {
            return Json::create($page)->header([
                self::HeaderPrefix => true,
            ]);
        }

        return View::display($this->rootView, $this->viewData + ['page' => $page]);
    }

    public function status(int $code): self
    {
        $this->code = $code;

        return $this;
    }
}