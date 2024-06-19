<?php

namespace Usermp\LaravelGenerator\Commands;

use Illuminate\Console\Command;
use Usermp\LaravelGenerator\Generators\CrudGenerator;

class GenerateComponents extends Command
{
    protected $signature = 'generate:crud {yamlFile}';
    protected $description = 'Generate Laravel CRUD from a YAML file';

    public function handle()
    {
        $yamlFile = $this->argument('yamlFile');
        $generator = new CrudGenerator($yamlFile);
        $generator->generate();
        $this->info('CRUD generated successfully.');
    }
}
