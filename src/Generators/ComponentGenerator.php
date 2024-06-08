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

        $this->generateModel($data['model']);
        $this->generateMigration($data['migration']);
        $this->generateFactory($data['factory']);
        $this->generateController($data['controller']);
        $this->generateRoutes($data['routes']);
        $this->generateFormRequest($data['form_request']);
        $this->generateMailable($data['mailable']);
        $this->generateJob($data['job']);
        $this->generateEvent($data['event']);
        $this->generateBladeTemplate($data['blade']);
        $this->generateHttpTest($data['http_test']);
        $this->generateUnitTest($data['unit_test']);
    }

    protected function generateModel($modelData)
    {
        $stub = File::get(__DIR__ . '/../stubs/model.stub');
        $content = str_replace(
            ['{{modelName}}', '{{fillable}}', '{{casts}}', '{{dates}}', '{{relationships}}'],
            [
                $modelData['name'],
                $this->formatArray($modelData['fillable']),
                $this->formatAssociativeArray($modelData['casts']),
                $this->formatArray($modelData['dates']),
                $this->generateRelationships($modelData['relationships'])
            ],
            $stub
        );

        $path = app_path('Models/' . $modelData['name'] . '.php');
        File::put($path, $content);
    }

    protected function generateMigration($migrationData)
    {
        $tableName = Str::snake(Str::pluralStudly($migrationData['name']));
        $stub = File::get(__DIR__ . '/../stubs/migration.stub');
        $content = str_replace(
            ['{{tableName}}', '{{columns}}'],
            [$tableName, $this->generateMigrationColumns($migrationData['columns'])],
            $stub
        );

        $fileName = date('Y_m_d_His') . '_create_' . $tableName . '_table.php';
        $path = database_path('migrations/' . $fileName);
        File::put($path, $content);
    }

    protected function generateFactory($factoryData)
    {
        $stub = File::get(__DIR__ . '/../stubs/factory.stub');
        $content = str_replace(
            ['{{modelName}}', '{{definition}}'],
            [$factoryData['name'], $this->generateFactoryDefinition($factoryData['fields'])],
            $stub
        );

        $path = database_path('factories/' . $factoryData['name'] . 'Factory.php');
        File::put($path, $content);
    }

    protected function generateController($controllerData)
    {
        $stub = File::get(__DIR__ . '/../stubs/controller.stub');
        $content = str_replace(
            ['{{controllerName}}', '{{modelName}}', '{{indexMethod}}', '{{storeMethod}}'],
            [
                $controllerData['name'],
                $controllerData['model'],
                $this->generateControllerMethod('index', $controllerData['index']),
                $this->generateControllerMethod('store', $controllerData['store'])
            ],
            $stub
        );

        $path = app_path('Http/Controllers/' . $controllerData['name'] . '.php');
        File::put($path, $content);
    }

    protected function generateRoutes($routesData)
    {
        $stub = File::get(__DIR__ . '/../stubs/routes.stub');
        $content = str_replace(
            ['{{controllerName}}', '{{routes}}'],
            [$routesData['controller'], $this->generateRouteDefinitions($routesData['actions'])],
            $stub
        );

        $path = base_path('routes/web.php');
        File::append($path, $content);
    }

    protected function generateFormRequest($formRequestData)
    {
        $stub = File::get(__DIR__ . '/../stubs/form_request.stub');
        $content = str_replace(
            ['{{requestName}}', '{{rules}}'],
            [$formRequestData['name'], $this->generateValidationRules($formRequestData['rules'])],
            $stub
        );

        $path = app_path('Http/Requests/' . $formRequestData['name'] . '.php');
        File::put($path, $content);
    }

    protected function generateMailable($mailableData)
    {
        $stub = File::get(__DIR__ . '/../stubs/mailable.stub');
        $content = str_replace(
            ['{{mailableName}}', '{{property}}'],
            [$mailableData['name'], $mailableData['property']],
            $stub
        );

        $path = app_path('Mail/' . $mailableData['name'] . '.php');
        File::put($path, $content);
    }

    protected function generateJob($jobData)
    {
        $stub = File::get(__DIR__ . '/../stubs/job.stub');
        $content = str_replace(
            ['{{jobName}}', '{{property}}'],
            [$jobData['name'], $jobData['property']],
            $stub
        );

        $path = app_path('Jobs/' . $jobData['name'] . '.php');
        File::put($path, $content);
    }

    protected function generateEvent($eventData)
    {
        $stub = File::get(__DIR__ . '/../stubs/event.stub');
        $content = str_replace(
            ['{{eventName}}', '{{property}}'],
            [$eventData['name'], $eventData['property']],
            $stub
        );

        $path = app_path('Events/' . $eventData['name'] . '.php');
        File::put($path, $content);
    }

    protected function generateBladeTemplate($bladeData)
    {
        $stub = File::get(__DIR__ . '/../stubs/blade.stub');
        $content = str_replace('{{templateContent}}', $bladeData['content'], $stub);

        $path = resource_path('views/' . str_replace('.', '/', $bladeData['name']) . '.blade.php');
        File::put($path, $content);
    }

    protected function generateHttpTest($httpTestData)
    {
        $stub = File::get(__DIR__ . '/../stubs/http_test.stub');
        $content = str_replace(
            ['{{testName}}', '{{methods}}'],
            [$httpTestData['name'], $this->generateTestMethods($httpTestData['methods'])],
            $stub
        );

        $path = base_path('tests/Feature/' . $httpTestData['name'] . '.php');
        File::put($path, $content);
    }

    protected function generateUnitTest($unitTestData)
    {
        $stub = File::get(__DIR__ . '/../stubs/unit_test.stub');
        $content = str_replace(
            ['{{testName}}', '{{methods}}'],
            [$unitTestData['name'], $this->generateTestMethods($unitTestData['methods'])],
            $stub
        );

        $path = base_path('tests/Unit/' . $unitTestData['name'] . '.php');
        File::put($path, $content);
    }

    protected function formatArray($array)
    {
        return implode(",\n        ", array_map(function ($item) {
            return "'$item'";
        }, $array));
    }

    protected function formatAssociativeArray($array)
    {
        return implode(",\n        ", array_map(function ($key, $value) {
            return "'$key' => '$value'";
        }, array_keys($array), $array));
    }

    protected function generateRelationships($relationships)
    {
        return implode("\n\n    ", array_map(function ($relationship) {
            return "public function {$relationship['method']}()\n    {\n        return \$this->{$relationship['type']}({$relationship['model']}::class);\n    }";
        }, $relationships));
    }

    protected function generateMigrationColumns($columns)
    {
        return implode("\n            ", array_map(function ($column) {
            return "\$table->{$column['type']}('{$column['name']}');";
        }, $columns));
    }

    protected function generateFactoryDefinition($fields)
    {
        return implode(",\n            ", array_map(function ($field) {
            return "'{$field['name']}' => \$this->faker->{$field['faker']}";
        }, $fields));
    }

    protected function generateControllerMethod($method, $content)
    {
        return "public function {$method}()\n    {\n        {$content}\n    }";
    }

    protected function generateRouteDefinitions($actions)
    {
        return implode("\n", array_map(function ($action) {
            return "\$router->{$action['method']}('{$action['uri']}', '{$action['controller']}@{$action['action']}');";
        }, $actions));
    }

    protected function generateValidationRules($rules)
    {
        return implode(",\n            ", array_map(function ($rule) {
            return "'{$rule['field']}' => '{$rule['rule']}'";
        }, $rules));
    }
        
    protected function generateTestMethods($methods)
    {
        return implode("\n\n    ", array_map(function ($method) {
            return "public function test{$method['name']}()\n    {\n        {$method['content']}\n    }";
        }, $methods));
    }
}
                