<?php

namespace Usermp\LaravelGenerator\Generators;

use Symfony\Component\Yaml\Yaml;

class CrudGenerator
{
    protected $yamlFile;

    /**
     * ComponentGenerator constructor.
     *
     * @param string $yamlFile
     */
    public function __construct($yamlFile)
    {
        $this->yamlFile = $yamlFile;
    }

    /**
     * Generate the necessary files from the YAML configuration.
     */
    public function generate()
    {
        $data = Yaml::parseFile($this->yamlFile);

        if( ! $data['service'] ) return null;

        $modelGenerator = new ModelGenerator();
        $modelGenerator->generateModel($data['service']);

        $controllerGenerator = new ControllerGenerator();
        $controllerGenerator->generateController($data['service']);

        $controllerGenerator = new RequestGenerator();
        $controllerGenerator->generateRequests($data['service']);

        $migrationGenerator = new MigrationGenerator();
        $migrationGenerator->generateMigration($data['service']);

        $migrationGenerator = new RouteGenerator();
        $migrationGenerator->generateRoute($data['service']);
    }
}
