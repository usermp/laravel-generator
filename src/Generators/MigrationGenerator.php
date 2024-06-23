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

                $fieldLines[] = "\$table->string('$field')" . (in_array("nullable", $rules) ? "->nullable()" : "") . ";";

            } elseif (in_array('text', $rules)) {

                $fieldLines[] = "\$table->text('$field')" . (in_array("nullable", $rules) ? "->nullable()" : "") . ";";

            } elseif (in_array('timestamp', $rules)) {

                $fieldLines[] = "\$table->timestamp('$field')->nullable();";

            } elseif (in_array('integer', $rules)) {
                if(str_contains($field,"_id")){
                    $fieldLines[] = "\$table->unsignedBigInteger('$field');";
                    $fieldLines[] = "\$table->foreign('$field')->references('id')->on('" . Str::plural($this->relationTable($rules)) . "')->onDelete('cascade');";
                }else{
                    $fieldLines[] = "\$table->integer('$field')" . (in_array("nullable", $rules) ? "->nullable()" : "") . ";";
                }
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
    private function relationTable($rules)
    {
        foreach($rules as $rule)
        {
            if(str_contains($rule,"#"))
            {
                return explode("#",$rule)[0];
            }
        }
    }
}
