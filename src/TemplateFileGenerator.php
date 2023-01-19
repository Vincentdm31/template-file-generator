<?php

namespace Laravins\TemplateFileGenerator;

use Exception;
use Illuminate\Support\Facades\File;

class TemplateFileGenerator
{
    /**
     * Config file
     */
    protected $config;

    /**
     * Path of the config file to use.
     */
    protected $config_path;

    /**
     * Result returned at the end of the generation process.
     */
    protected $result = ['status' => 'error', 'message' => ''];

    /**
     * Allowed prefixes for target path
     */
    protected $prefixes = [
        'app',
        'base',
        'config',
        'resource',
        'database',
        'lang',
        'public',
        'storage'
    ];

    /**
     * Template folder path used to generate files
     */
    protected $base_path = '';

    /**
     * Template folder path prefix
     */

    protected $base_path_prefix = '';
    /**
     * Target folder where files were be generated.
     */
    protected $target_path = '';

    /**
     * Target folder path prefix
     */
    protected $target_path_prefix = '';

    /**
     * Set config path
     * @param string $config_path
     */
    public function __construct($config_path)
    {
        $this->config_path = $config_path;

        $this->checkConfig();
    }

    public function getResult()
    {
        return $this->result;
    }

    public function checkConfig()
    {
        if (!$this->checkConfigFilePath()) return;
        if (!$this->checkConfigRequiredKeys()) return;
        if (!$this->checkConfigPrefixes()) return;
        if (!$this->checkIfTemplateFolderExist()) return;
        if (!$this->checkIfTemplateFilesExist()) return;
        if (!$this->replaceContent()) return;
    }

    /**
     * Check if config file exist.
     */
    public function checkConfigFilePath()
    {
        if (!is_file(config_path('generator/' . $this->config_path . '.php'))) {
            $this->result["message"] = 'Config file ' . $this->config_path . ' does not exist !';

            return false;
        }

        $this->config = config('generator.' . $this->config_path);

        return true;
    }

    public function checkConfigRequiredKeys()
    {
        $needed_keys = ['files', 'config'];

        foreach ($needed_keys as $key => $value) {
            if (!isset($this->config[$value])) {
                $this->result["message"] = 'Required array key [' . $value . '] in config: ' . $this->config_path . ' should be set.';

                return false;
            }

            if (!is_array($this->config[$value])) {
                $this->result["message"] = 'Array value of key [' . $value . '] in config: ' . $this->config_path . ' needs to be an array.';

                return false;
            }

            if ($value === 'files') {
                foreach ($this->config[$value] as $k => $v)
                    if (!is_array($this->config[$value][$k])) {
                        $this->result["message"] = 'Array value of key [' . $value . '.' . $k . '] in config: ' . $this->config_path . ' needs to be an array.';

                        return false;
                    }
            }

            if ($value === 'config') {
                $needed_paths = ['base', 'target'];

                foreach ($needed_paths as $k => $v) {
                    if (!isset($this->config[$value][$v . '_path'])) {
                        $this->result["message"] = '[config.' . $v . '_path] in config: ' . $this->config_path . ' needs to be set.';

                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function checkConfigPrefixes()
    {
        $prefixes = ['base', 'target'];

        $path_prefixes = [
            'app' => app_path(),
            'base' => base_path(),
            'config' => config_path(),
            'resource' => resource_path(),
            'database' => database_path(),
            'lang' => lang_path(),
            'public' => public_path(),
            'storage' => storage_path(),
        ];

        foreach ($prefixes as $k => $v) {
            if (isset($this->config['config'][$v . '_path_prefix'])) {
                if (strlen($this->config['config'][$v . '_path_prefix'])) {
                    if (!in_array($this->config['config'][$v . '_path_prefix'], array_values($this->prefixes))) {
                        $this->result["message"] = $v . '_path_prefix [' . $this->config['config'][$v . '_path_prefix'] . '] in config: ' . $this->config_path . ' is not a valid prefix.';

                        return false;
                    }

                    $this->{$v . '_path'} = $this->config['config'][$v . '_path'];
                    $this->{$v . '_path_prefix'} = $path_prefixes[$this->config['config'][$v . '_path_prefix']];
                }
            } else {
                $this->result["message"] = 'config.' . $v . '_path_prefix in config: ' . $this->config_path . ' needs to be set (may be empty).';

                return false;
            }
        }

        return true;
    }

    public function checkIfTemplateFolderExist()
    {
        if (!is_dir(strlen($this->base_path_prefix) ? $this->base_path_prefix . '/' . $this->base_path : $this->base_path)) {
            $this->result["message"] = 'Template folder [' . $this->config['config']['base_path'] . '] located at [' . $this->base_path_prefix . '/' . $this->config['config']['base_path'] . '] in config: ' . $this->config_path . ' does not exist.';

            return false;
        }

        return true;
    }

    public function checkIfTemplateFilesExist()
    {
        foreach ($this->config['files'] as $k => $v) {
            if (!is_file(strlen($this->base_path_prefix) ? $this->base_path_prefix . '/' . $this->base_path . '/' . $k : $this->base_path . '/' . $k)) {
                $this->result["message"] = 'Template file [' . $k . '] located at [' . (strlen($this->base_path_prefix) ? $this->base_path_prefix . '/' . $this->base_path . '/' . $k : $this->base_path . '/' . $k) . '] doest not exist.';

                return false;
            }
        }

        return true;
    }

    public function replaceContent()
    {
        $path_prefixes = [
            'app' => app_path(),
            'base' => base_path(),
            'config' => config_path(),
            'resource' => resource_path(),
            'database' => database_path(),
            'lang' => lang_path(),
            'public' => public_path(),
            'storage' => storage_path(),
        ];

        foreach ($this->config['files'] as $k => $v) {
            $target_folder_path = strlen($this->config['config']['target_path_prefix']) ?  $path_prefixes[$this->config['config']['target_path_prefix']] . '/' . $this->config['config']['target_path']  : $this->config['config']['target_path'];
            $target_file_path = strlen($this->config['config']['target_path_prefix']) ?  $path_prefixes[$this->config['config']['target_path_prefix']] . '/' . $this->config['config']['target_path'] . '/' . $k : $this->config['config']['target_path'] . '/' . $k;
            $base_file_path = strlen($this->config['config']['base_path_prefix']) ?  $path_prefixes[$this->config['config']['base_path_prefix']] . '/' . $this->config['config']['base_path'] . '/' . $k : $this->config['config']['base_path'] . '/' . $k;

            if (!File::isDirectory($target_folder_path)) {
                File::makeDirectory($target_folder_path, 0777, true, true);
            }

            copy($base_file_path, $target_file_path);

            if (is_writeable($target_file_path)) {
                try {
                    $FileContent = file_get_contents($base_file_path);
                    foreach ($v as $key => $val) {

                        $FileContent = str_replace('%' . $key . '%', $val, $FileContent);
                        if (file_put_contents($target_file_path, $FileContent) > 0) {
                            $this->result["status"] = 'success';
                        } else {
                            $this->result["message"] = 'Error while writing file';
                        }
                    }
                } catch (Exception $e) {
                    $this->result["message"] = 'Error : ' . $e;
                }
            } else {
                dd('not writable');
            }
        }
    }
}
