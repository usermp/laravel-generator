<?php

namespace Usermp\LaravelGenerator\Generators;

class GeneratorUtils
{
    public static function formatArray($array)
    {
        return implode(",\n        ", array_map(function ($item) {
            return "'$item'";
        }, $array));
    }

    public static function formatAssociativeArray($array)
    {
        return implode(",\n        ", array_map(function ($key, $value) {
            return "'$key' => '$value'";
        }, array_keys($array), $array));
    }

    public static function generateRelationships($relationships)
    {
        return implode("\n\n    ", array_map(function ($relationship) {
            return "public function {$relationship['method']}()\n    {\n        return \$this->{$relationship['type']}({$relationship['model']}::class);\n    }";
        }, $relationships));
    }

    public static function generateControllerIndex($model)
    {
        $modelVar = strtolower($model);
        return "public function index()\n    {\n        return Crud::index(new $model);\n    }";
    }

    public static function generateControllerStore($model)
    {
        $modelVar = strtolower($model);
        return "public function store(Store{$model}Request \$request, $model \$$modelVar)\n    {\n        \$validated = \$request->validated();\n        return Crud::store(\$validated, \$$modelVar);\n    }";
    }

    public static function generateControllerShow($model)
    {
        $modelVar = strtolower($model);
        return "public function show($model \$$modelVar)\n    {\n        return Crud::show(\$$modelVar);\n    }";
    }

    public static function generateControllerUpdate($model)
    {
        $modelVar = strtolower($model);
        return "public function update(Update{$model}Request \$request, $model \$$modelVar)\n    {\n        \$validated = \$request->validated();\n        return Crud::update(\$validated, \$$modelVar);\n    }";
    }

    public static function generateControllerDelete($model)
    {
        $modelVar = strtolower($model);
        return "public function destroy($model \$$modelVar)\n    {\n        return Crud::destroy(\$$modelVar);\n    }";
    }
}
