<?php

namespace App\Traits;

use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Blade;
use ReflectionClass;

trait HasWireInit
{
    /**
     * Indicates whether the component is ready to be loaded.
     *
     * @var bool
     */
    protected $readyToLoad = false;

    /**
     * Adds wire:init directives to the Blade component for the specified methods.
     *
     * @param array $methods An associative array of method names and wire:init actions.
     *
     * @return void
     */
    public static function wireInit(array $methods)
    {
        $class = new ReflectionClass(static::class);

        foreach ($methods as $methodName => $action) {
            $method = $class->getMethod($methodName);

            Blade::component(static::class, static::class, [
                "wire:init.$action" => $methodName,
            ]);
        }
    }

    /**
     * Automatically add wire:init directives to Blade components for specified methods.
     *
     * @param array $methods An associative array of method names and wire:init actions.
     *
     * @throws InvalidArgumentException If a method name is not present in the component.
     */
    public function registerWireInit(array $methods)
    {
        foreach ($methods as $methodName => $action) {
            $this->validateMethodName($methodName);

            Blade::component(static::class, static::class, [
                "wire:init.$action" => $methodName,
            ]);
        }
    }

    /**
     * Ensure the specified method exists in the component.
     *
     * @param string $methodName The name of the method to validate.
     *
     * @throws InvalidArgumentException If the method is not found.
     */
    private function validateMethodName(string $methodName)
    {
        if (!method_exists($this, $methodName)) {
            throw new InvalidArgumentException("Method `$methodName` does not exist in " . static::class);
        }
    }

    /**
     * Refreshes the page.
     *
     * This will set the `readyToLoad` property to `true`, indicating that the component is ready to be loaded.
     *
     * @return void
     */
    public function refreshPage()
    {
        $this->readyToLoad = true;
    }
}
