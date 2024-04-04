<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HasWireInit
{
    /**
     * Indicates whether the component is ready to be loaded.
     *
     * @var bool
     */
    protected $readyToLoad = false;

    public function loadComponent()
    {
        $this->readyToLoad = true;
        Log::info('load component');
    }
}
