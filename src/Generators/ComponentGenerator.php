<?php

namespace Usermp\LaravelGenerator\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class ComponentGenerator
{
    protected $yamlFile;

    public function __construct($yamlFile)
    {
        $this->yamlFile = $yamlFile;
    }

    public function generate()
    {
        $data = Yaml::parseFile($this->yamlFile);
        $this->generateController($data['controller']);
    }
    protected function generateController($controllerData)
    {
        $stub = File::get(__DIR__ . '/../stubs/controller.stub');
        $content = str_replace(
            ['{{controllerName}}', '{{modelName}}', '{{indexMethod}}', '{{storeMethod}}', '{{showMethod}}', '{{updateMethod}}', '{{deleteMethod}}'],
            [
                $controllerData['name'],
                $controllerData['model'],
                $this->generateControllerMethod('index' , ""),
                $this->generateControllerMethod('store' , ""),
                $this->generateControllerMethod('show'  , ""),
                $this->generateControllerMethod('update', ""),
                $this->generateControllerMethod('delete', "")
            ],
            $stub
        );

        $path = app_path('Http/Controllers/' . $controllerData['name'] . '.php');
        File::put($path, $content);
    }



    protected function generateControllerMethod($method, $content)
    {
        return "public function {$method}()\n    {\n        {$content}\n    }";
    }

}
