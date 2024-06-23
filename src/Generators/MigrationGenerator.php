<?php

namespace Usermp\LaravelGenerator\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MigrationGenerator
{
    public function generateMigration($service)
    {
        $name = $service['name'];
        $fields = $service['fields'];

        // Pluralize the table name
        $tableName = Str::plural(strtolower($name));

        $fieldLines = [];
        foreach ($fields as $field => $rules) {
            if (in_array('string', $rules)) {
                $fieldLines[] = "\$table->string('$field');";
            } elseif (in_array('text', $rules)) {
                $fieldLines[] = "\$table->text('$field');";
            } elseif (in_array('timestamp', $rules)) {
                $fieldLines[] = "\$table->timestamp('$field')->nullable();";
            } elseif (in_array('integer', $rules)) {
                $fieldLines[] = "\$table->integer('$field');";
            }
        }

        $replacements = [
            '{{tableName}}' => $tableName,
            '{{fields}}' => implode("\n            ", $fieldLines),
        ];

        $stubPath = __DIR__ . '/../stubs/migration.stub';
        $stub = file_get_contents($stubPath);

        $migrationContent = str_replace(array_keys($replacements), array_values($replacements), $stub);

        $migrationFileName = date('Y_m_d_His') . '_create_' . $tableName . '_table.php';
        $migrationPath = database_path('migrations/' . $migrationFileName);

        file_put_contents($migrationPath, $migrationContent);
    }
}
