<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class ApiRequestMakeCommand extends GeneratorCommand
{
    protected $signature = 'make:apiRequest {name : Class name} {module : Module name}';

    protected $description = 'Make an API FormRequest for a specific module';

    protected $type = 'API Request';

    protected function getStub()
    {
        return __DIR__ . '/stubs/ApiRequest.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $module = Str::studly($this->argument('module'));

        return "Modules\\{$module}\\Http\\Requests";
    }

    protected function getPath($name)
    {
        $module = Str::studly($this->argument('module'));
        $className = class_basename($name);

        $directory = base_path("Modules/{$module}/app/Http/Requests");

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        return $directory . '/' . $className . '.php';
    }

    protected function qualifyClass($name)
    {
        $name = Str::studly(class_basename($name));
        $namespace = $this->getDefaultNamespace(trim($this->rootNamespace(), '\\'));

        return $namespace . '\\' . $name;
    }
}
