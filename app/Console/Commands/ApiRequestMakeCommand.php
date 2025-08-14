<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class ApiRequestMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:apiRequest {name}';

    /**
     * The console command description.
     *
     * @var string 
     */
    protected $description = 'make api request for api form request';

    protected function getStub()
    {
        return __DIR__.'/stubs/ApiRequest.stub';
    }

    public function getDefaultNamespace($rootNamespace){
        return "$rootNamespace/Http/ApiRequest";
    }
}
