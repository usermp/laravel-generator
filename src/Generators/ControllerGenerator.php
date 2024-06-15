<?php

namespace Usermp\LaravelGenerator\Generators;

use Illuminate\Support\Facades\File;

class ControllerGenerator
{
    public function generateController(array $controllerData)
    {
        if (empty($controllerData)) return;
        $stub = File::get(__DIR__ . '/../stubs/controller.stub');
        $content = str_replace(
            ['{{controllerName}}', '{{modelName}}', '{{indexMethod}}', '{{storeMethod}}', '{{showMethod}}', '{{updateMethod}}', '{{deleteMethod}}'],
            [
                $controllerData['name'],
                $controllerData['model'],
                GeneratorUtils::generateControllerIndex($controllerData['model']),
                GeneratorUtils::generateControllerStore($controllerData['model']),
                GeneratorUtils::generateControllerShow($controllerData['model']),
                GeneratorUtils::generateControllerUpdate($controllerData['model']),
                GeneratorUtils::generateControllerDelete($controllerData['model']),
            ],
            $stub
        );

        $path = app_path('Http/Controllers/' . $controllerData['name'] . '.php');
        File::put($path, $content);
    }
}
