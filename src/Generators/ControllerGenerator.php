<?php

namespace Usermp\LaravelGenerator\Generators;

use Illuminate\Support\Facades\File;

class ControllerGenerator
{
    public function generateController(array $controllerData): void
    {
        if (empty($controllerData)) {
            return;
        }

        $stub = File::get(__DIR__ . '/../stubs/controller.stub');

        $replacements = [
            '{{controllerName}}' => $controllerData['name'] . "Controller",
            '{{modelName}}' => $controllerData['name'],
            '{{indexMethod}}' => GeneratorUtils::generateControllerIndex($controllerData['name']),
            '{{storeMethod}}' => GeneratorUtils::generateControllerStore($controllerData['name']),
            '{{showMethod}}' => GeneratorUtils::generateControllerShow($controllerData['name']),
            '{{updateMethod}}' => GeneratorUtils::generateControllerUpdate($controllerData['name']),
            '{{deleteMethod}}' => GeneratorUtils::generateControllerDelete($controllerData['name']),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);

        $path = app_path('Http/Controllers/' . $controllerData['name'] . 'Controller.php');
        File::put($path, $content); 
    }
}