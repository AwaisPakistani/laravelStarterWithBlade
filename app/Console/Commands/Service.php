<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class Service extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service
                            {name : The name of the Service}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Service Created Successfully';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serviceName = $this->argument('name');

        $this->generateService($serviceName);
    }
    protected function generateService($serviceName)
    {
        $ServicePath = app_path("Services/{$serviceName}Service.php");

        if (!File::exists(dirname($ServicePath))) {
            File::makeDirectory(dirname($ServicePath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/service.stub');
        $stub = str_replace('{{ServiceName}}', $serviceName, $stub);

        File::put($ServicePath, $stub);
    }
}
