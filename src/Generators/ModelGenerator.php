<?php

namespace Usermp\LaravelGenerator\Generators;

use Illuminate\Support\Facades\File;

class ModelGenerator
{
    public function generateModel($modelData)
    {
        if (empty($modelData)) return;
        $modelName = $modelData['modelName'];
        unset($modelData['modelName']);

        $traits = $modelData['traits'] ?? [];
        unset($modelData['traits']);

        $traitsList = '';

        foreach ($traits as $trait) {
            $traitsList .= ", $trait";
            $useTraits[] = "use $trait;";
        }

        $fillable = array_keys($modelData);
        $casts = [];
        $dates = [];
        $relationships = [];

        foreach ($modelData as $field => $type) {
            if (strpos($type, 'nullable') !== false) {
                $dates[] = $field;
            }
            if (strpos($type, 'id:') !== false) {
                [$type, $relatedModel] = explode(':', $type);
                $relationships[] = [
                    'method' => $relatedModel,
                    'type' => 'belongsTo',
                    'model' => ucfirst($relatedModel)
                ];
            }
            if ($type === 'timestamp' || $type === 'nullable timestamp') {
                $casts[$field] = 'datetime';
            }
        }

        $stub = File::get(__DIR__ . '/../stubs/model.stub');
        $content = str_replace(
            ['{{modelName}}', '{{useTraits}}', '{{traitsList}}', '{{fillable}}', '{{casts}}', '{{dates}}', '{{relationships}}'],
            [
                $modelName,
                implode("\n", $useTraits),
                $traitsList,
                formatArray($fillable),
                formatAssociativeArray($casts),
                formatArray($dates),
                generateRelationships($relationships)
            ],
            $stub
        );

        $path = app_path('Models/' . $modelName . '.php');
        File::put($path, $content);
    }
}
