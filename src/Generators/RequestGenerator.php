<?php

namespace Usermp\LaravelGenerator\Generators;

use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\File;

class RequestGenerator
{
    public function generateRequests(array $modelData): void
    {
        if (empty($modelData)) {
            return;
        }

        $stub = File::get(__DIR__ . '/../stubs/Request.stub');

        $this->createRequestFile(
            "Store" . ucfirst($modelData['name']) . "Request",
            $this->generateValidationStoreRules($modelData['fields']),
            $stub
        );

        $this->createRequestFile(
            "Update" . ucfirst($modelData['name']) . "Request",
            $this->generateValidationUpdateRules($modelData['fields']),
            $stub
        );
    }

    protected function generateValidationStoreRules(array $fields): string
    {
        return $this->generateValidationRules($fields, function ($rule) {
            return $rule === 'text' ? 'string' : (!str_contains($rule, '#') ? $rule : null);
        });
    }

    protected function generateValidationUpdateRules(array $fields): string
    {
        return $this->generateValidationRules($fields, function ($rule) {
            if ($rule === 'required') {
                return null;
            }
            return $rule === 'text' ? 'string' : (!str_contains($rule, '#') ? $rule : null);
        });
    }

    private function generateValidationRules(array $fields, callable $ruleFilter): string
    {
        $rulesArray = array_map(function ($item) use ($ruleFilter) {
            return array_filter(array_map($ruleFilter, $item));
        }, $fields);

        $rulesString = '';
        foreach ($rulesArray as $field => $ruleSet) {
            $rulesString .= "           '$field' => '" . implode('|', $ruleSet) . "',\n";
        }

        return $rulesString;
    }

    private function createRequestFile(string $requestName, string $rules, string $stub): void
    {
        $content = str_replace(
            ['{{requestName}}', '{{rules}}'],
            [$requestName, $rules],
            $stub
        );

        $directory = app_path('Http/Requests');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $path = $directory . '/' . $requestName . '.php';
        File::put($path, $content);
    }
}
