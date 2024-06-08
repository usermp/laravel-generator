<?php

namespace Usermp\LaravelGenerator\Console\Commands;

use Illuminate\Console\Command;
use YourPackage\Generators\ComponentGenerator;

class GenerateComponents extends Command
{
    protected $signature = 'generate:components {yamlFile}';
    protected $description = 'Generate Laravel components from a YAML file';

    public function handle()
    {
        $yamlFile = $this->argument('yamlFile');
        $generator = new ComponentGenerator($yamlFile);
        $generator->generate();
        $this->info('Components generated successfully.');
    }
}
