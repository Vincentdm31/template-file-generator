<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravins\TemplateFileGenerator\TemplateFileGenerator;

class TemplateFileGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravins-tfg:generate {path?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate files based on template with data customization.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $config_path = $this->argument('path');
        $config = null;

        if (!$config_path) {
            $this->error('laravins-file-generator:generate error: please provider a path.');
            $this->error('');
            $this->error('Example:');
            $this->error('');
            $this->error('File structure');
            $this->error('- project/');
            $this->error('-- config/');
            $this->error('--- my-customs-generators/');
            $this->error('---- example-generator.php');
            $this->error('');
            $this->error('Command: php artisan laravins-tfg:generate my-customs-generators/example-generator');

            return false;
        } else {
            $this->info('Checking if configuration file located at: <' . config_path() . '/' . $config_path . '.php> exist ...');

            if (is_file(config_path() . '/' . $config_path . '.php')) {
                $this->info('Configuration file found.');
                $this->info('Verifying configuration file ...');

                $config = new TemplateFileGenerator($config_path);

                $result = $config->getResult();

                if ($result['status'] === "config__check_success") {
                    $this->info('Configuration file verification end successfully.');

                    $result = $config->replaceContent();

                    if ($result['status'] === 'success') {
                        $this->info('Laravins-tfg:generate ' . $config_path . ': ' . $result['status'] . ', ' . $result['message'] . '.');

                        return true;
                    }

                    $this->error('Laravins-pfg:generate ' . $config_path . ' error: ' . $result['message']);

                    return false;
                } else {
                    $this->error('Laravins-tfg:generator error: ' . $result['status'] . ', ' . $result['message']);

                    return false;
                }
            } else {
                $this->error('Laravins-tfg:generator error: Configuration file located at: <' . config_path() . '/' . $config_path . '.php> not found.');

                return false;
            }
        }
    }
}
