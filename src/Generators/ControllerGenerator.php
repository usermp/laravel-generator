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
            '{{controllerName}}' => $controllerData['name'],
            '{{modelName}}' => $controllerData['model'],
            '{{indexMethod}}' => GeneratorUtils::generateControllerIndex($controllerData['model']),
            '{{storeMethod}}' => GeneratorUtils::generateControllerStore($controllerData['model']),
            '{{showMethod}}' => GeneratorUtils::generateControllerShow($controllerData['model']),
            '{{updateMethod}}' => GeneratorUtils::generateControllerUpdate($controllerData['model']),
            '{{deleteMethod}}' => GeneratorUtils::generateControllerDelete($controllerData['model']),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);

        $path = app_path('Http/Controllers/' . $controllerData['name'] . '.php');
        File::put($path, $content);
    }
}