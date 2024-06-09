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
                $this->controllerIndex($controllerData['model']),
                $this->controllerStore($controllerData['model']),
                $this->controllerShow($controllerData['model']),
            ],
            $stub
        );

        $path = app_path('Http/Controllers/' . $controllerData['name'] . '.php');
        File::put($path, $content);
    }

    public function controllerIndex($model)
    {
        return "public function index($model $" .strtolower($model).
         ")\n    {\n        return Crud::index($" . strtolower($model) . "); \n    }";
    }
    public function controllerStore($model)
    {
        return "public function store(Store{$model}Request ".
        "$" ."request, $model $" . strtolower($model) .
        ")\n    {\n        ".
        "$"."validated = "."$"."request->validated();\n        return Crud::store($" .
        "validated ,$" . strtolower($model) . "); \n    }";
    }
    public function controllerShow($model)
    {
        return "public function show($model $" .strtolower($model).
        ")\n    {\n        return Crud::show($" . strtolower($model) . "); \n    }";
    }
    public function controllerUpdate($model)
    {
        return "Response::success('success',$model::all())";
    }
    public function controllerDelete($model)
    {
        return "Response::success('success',$model::all())";
    }

}
