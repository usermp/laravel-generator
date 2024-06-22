<?php

namespace Usermp\LaravelGenerator\Generators;

use Illuminate\Support\Facades\File;

class MigrationGenerator
{
    public function generateMigration($service)
    {
        $name = $service['name'];
        $fields = $service['fields'];

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
            '{{tableName}}' => strtolower($name),
            '{{fields}}' => implode("\n            ", $fieldLines),
        ];

        $stubPath = __DIR__ . '/../stubs/migration.stub';
        $stub = file_get_contents($stubPath);

        $migrationContent = str_replace(array_keys($replacements), array_values($replacements), $stub);

        $migrationFileName = date('Y_m_d_His') . '_create_' . strtolower($name) . '_table.php';
        $migrationPath = database_path('migrations/' . $migrationFileName);

        file_put_contents($migrationPath, $migrationContent);
    }
}
