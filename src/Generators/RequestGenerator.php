<?php

namespace Usermp\LaravelGenerator\Generators;

use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\File;

class RequestGenerator
{
    public function generatorRequests($modelData)
    {
        if (empty($modelData)) {
            return;
        }

        $stub = File::get(__DIR__ . '/../stubs/Request.stub');
        $storeContent = str_replace(
            ['{{requestName}}', '{{rules}}'],
            ["Store" . strtolower($modelData['modelName']) . "Request", $this->generateValidationRules($modelData)],
            $stub
        );
        $path = app_path('Http/Requests/' . "Store" . strtolower($modelData['modelName']) . "Request" . '.php');
        File::put($path, $storeContent);
    }
    protected function generateValidationRules($rules)
    {
        dd($rules);
        return implode(",\n            ", array_map(function ($rule) {
            return "'{$rule['field']}' => '{$rule['rule']}'";
        }, $rules));
    }
}
