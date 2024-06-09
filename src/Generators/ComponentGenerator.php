<?php

namespace Usermp\LaravelGenerator\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class ComponentGenerator
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
        $this->generateController($data['controller']);
    }

    /**
     * Generate the controller file based on the stub and the provided data.
     *
     * @param array $controllerData
     */
    protected function generateController(array $controllerData)
    {
        $stub = File::get(__DIR__ . '/../stubs/controller.stub');
        $content = str_replace(
            ['{{controllerName}}', '{{modelName}}', '{{indexMethod}}', '{{storeMethod}}', '{{showMethod}}', '{{updateMethod}}', '{{deleteMethod}}'],
            [
                $controllerData['name'],
                $controllerData['model'],
                $this->generateControllerIndex($controllerData['model']),
                $this->generateControllerStore($controllerData['model']),
                $this->generateControllerShow($controllerData['model']),
                $this->generateControllerUpdate($controllerData['model']),
                $this->generateControllerDelete($controllerData['model']),
            ],
            $stub
        );

        $path = app_path('Http/Controllers/' . $controllerData['name'] . '.php');
        File::put($path, $content);
    }

    /**
     * Generate the index method for the controller.
     *
     * @param string $model
     * @return string
     */
    protected function generateControllerIndex($model)
    {
        $modelVar = strtolower($model);
        return "public function index($model \$$modelVar)\n    {\n        return Crud::index(\$$modelVar);\n    }";
    }

    /**
     * Generate the store method for the controller.
     *
     * @param string $model
     * @return string
     */
    protected function generateControllerStore($model)
    {
        $modelVar = strtolower($model);
        return "public function store(Store{$model}Request \$request, $model \$$modelVar)\n    {\n        \$validated = \$request->validated();\n        return Crud::store(\$validated, \$$modelVar);\n    }";
    }

    /**
     * Generate the show method for the controller.
     *
     * @param string $model
     * @return string
     */
    protected function generateControllerShow($model)
    {
        $modelVar = strtolower($model);
        return "public function show($model \$$modelVar)\n    {\n        return Crud::show(\$$modelVar);\n    }";
    }

    /**
     * Generate the update method for the controller.
     *
     * @param string $model
     * @return string
     */
    protected function generateControllerUpdate($model)
    {
        $modelVar = strtolower($model);
        return "public function update(Update{$model}Request \$request, $model \$$modelVar)\n    {\n        \$validated = \$request->validated();\n        return Crud::update(\$validated, \$$modelVar);\n    }";
    }

    /**
     * Generate the delete method for the controller.
     *
     * @param string $model
     * @return string
     */
    protected function generateControllerDelete($model)
    {
        $modelVar = strtolower($model);
        return "public function destroy($model \$$modelVar)\n    {\n        return Crud::destroy(\$$modelVar);\n    }";
    }
}
