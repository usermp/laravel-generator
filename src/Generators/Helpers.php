<?php

namespace Usermp\LaravelGenerator\Generators;

function formatArray($array)
{
    return implode(",\n        ", array_map(function ($item) {
        return "'$item'";
    }, $array));
}

function formatAssociativeArray($array)
{
    return implode(",\n        ", array_map(function ($key, $value) {
        return "'$key' => '$value'";
    }, array_keys($array), $array));
}

function generateRelationships($relationships)
{
    return implode("\n\n    ", array_map(function ($relationship) {
        return "public function {$relationship['method']}()\n    {\n        return \$this->{$relationship['type']}({$relationship['model']}::class);\n    }";
    }, $relationships));
}

function generateControllerIndex($model)
{
    $modelVar = strtolower($model);
    return "public function index()\n    {\n        return Crud::index(new $model);\n    }";
}

function generateControllerStore($model)
{
    $modelVar = strtolower($model);
    return "public function store(Store{$model}Request \$request)\n    {\n        \$validated = \$request->validated();\n        return Crud::store(\$validated, new $model);\n    }";
}

function generateControllerShow($model)
{
    $modelVar = strtolower($model);
    return "public function show($model \$$modelVar)\n    {\n        return Crud::show(\$$modelVar);\n    }";
}

function generateControllerUpdate($model)
{
    $modelVar = strtolower($model);
    return "public function update(Update{$model}Request \$request, $model \$$modelVar)\n    {\n        \$validated = \$request->validated();\n        return Crud::update(\$validated, \$$modelVar);\n    }";
}

function generateControllerDelete($model)
{
    $modelVar = strtolower($model);
    return "public function destroy($model \$$modelVar)\n    {\n        return Crud::destroy(\$$modelVar);\n    }";
}
