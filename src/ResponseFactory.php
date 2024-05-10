<?php
namespace Internia;


class ResponseFactory
{
    protected string $rootView = 'app';

    protected array $shareProps = [];

    protected string $version = '';

    public function setRootView(string $name): void
    {
        $this->rootView = $name;
    }

    public function share(string|array $key, $value = null): void
    {
        if (is_array($key)) {
            $this->shareProps = array_merge($this->shareProps, $key);
        } else {
            $this->shareProps[$key] = $value;
        }
    }

    public function getShared(string $key=null, $default = null)
    {
        if ($key) {
            return $this->shareProps[$key] ?? $default;
        }

        return $this->shareProps;
    }

    public function flushShared(): void
    {
        $this->shareProps = [];
    }

    public function version(string $version): void
    {
        $this->version = $version;
    }

    public function getVersion(): string 
    {
        return $this->version;
    }

    public function render(string $component, array $props = [])
    {
        return (new Response(
            $component,
            $props,
            $this->rootView,
            $this->getVersion(),
        ))->toResponse(request());
    }
}