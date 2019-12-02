<?php

namespace Trax\Foundation;

use \Illuminate\Foundation\Application;
use \Illuminate\Foundation\PackageManifest;
use \Illuminate\Foundation\ProviderRepository;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

class TraxApplication extends Application
{
    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders()
    {
        // All configured providers must be loaded before auto-discored providers.
        $providers = Collection::make($this->config['app.providers'])
            ->merge($this->make(PackageManifest::class)->providers())->toArray();

        (new ProviderRepository($this, new Filesystem, $this->getCachedServicesPath()))
                    ->load($providers);
    }

}
