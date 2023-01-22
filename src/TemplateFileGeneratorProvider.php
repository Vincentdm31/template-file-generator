<?php

namespace Laravins\TemplateFileGenerator;

use Illuminate\Support\Facades\Artisan;
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
            __DIR__ . '/config/example-generator.php' => config_path('template-file-generator/example-generator.php'),
        ], ['template-file-generator']);

        $this->publishes([
            __DIR__ . '/crud-views' => resource_path('template-file-generator/example-generator/crud-views'),
        ], ['template-file-generator']);

        $this->publishes([
            __DIR__ . '/commands/TemplateFileGeneratorCommand' => app_path('commands/TemplateFileGeneratorCommand.php'),
        ], ['template-file-generator']);

        Artisan::call('vendor:publish --tag=template-file-generator --ansi --force');
    }
}
