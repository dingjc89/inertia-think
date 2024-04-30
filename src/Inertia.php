<?php

namespace Internia;

use think\Facade;

/**
 * @method static version(\Closure $param)
 * @method static share(\Closure[] $share)
 * @method static setRootView(string $rootView)
 */
class Inertia extends Facade
{
    public static function getFacadeClass(): string
    {
        return ResponseFactory::class;
    }
}