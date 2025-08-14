<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class ApiRequestMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * php artisan make:apiRequest ClassName ModuleName
     *
     * @var string
     */
    protected $signature = 'make:apiRequest {name} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make API FormRequest for a specific module';

    protected function getStub()
    {
        return __DIR__.'/stubs/ApiRequest.stub';
    }

    public function getDefaultNamespace($rootNamespace)
    {
        $module = $this->argument('module');

        return "Modules\\{$module}\\Http\\Requests";
    }

    protected function getPath($name)
    {
        $module = $this->argument('module');
        $className = Str::afterLast($name, '\\');

        return base_path("Modules/{$module}/Http/Requests/{$className}.php");
    }
}
