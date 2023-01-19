<?php

namespace Appy\FcmHttpV1;

use Illuminate\Support\ServiceProvider;

class TemplateFileGeneratorProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/example-generator.php' => config_path('example-generator.php'),
        ], 'template-file-generator');
    }
}
