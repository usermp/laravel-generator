<?php

namespace Usermp\LaravelGenerator\Generators;

use Illuminate\Support\Facades\File;
use Usermp\LaravelGenerator\Generators\GeneratorUtils;

class ModelGenerator
{
    public function generateModel($modelData)
    {
        if (empty($modelData)) return;
        $modelName = $modelData['name'];
        $traits = $modelData['traits'] ?? [];
        $traitsList = '';
        $useTraits = [];

        foreach ($traits as $trait) {
            $name = explode("\\",$trait);
            $name = end($name);
            $traitsList .= ", $name";
            $useTraits[] = "use $trait;";
        }

        $fillable = array_keys($modelData['fields']);
        $casts = [];
        $dates = [];
        $relationships = [];

        foreach ($modelData['fields'] as $field => $type) {
            if (in_array("timestamp", $type) || in_array("datetime", $type)) {
                $dates[] = $field;
            }

            foreach($type as $item)
            {
                if(str_contains($item,"#"))
                {
                    $relationshipType = $this->getRelationshipType($item);
                    $relationships[] = [
                        'method' => explode("#",$item)[0],
                        'type'   => $relationshipType,
                        'model'  => ucfirst(explode("#",$item)[0])
                    ];
                }
            }
        }


        $stub = File::get(__DIR__ . '/../stubs/model.stub');
        $content = str_replace(
            ['{{modelName}}', '{{useTraits}}', '{{traitsList}}', '{{fillable}}', '{{casts}}', '{{dates}}', '{{relationships}}'],
            [
                $modelName,
                implode("\n", $useTraits),
                $traitsList,
                GeneratorUtils::formatArray($fillable),
                GeneratorUtils::formatAssociativeArray($casts),
                GeneratorUtils::formatArray($dates),
                $this->generateRelationships($relationships)
            ],
            $stub
        );

        $path = app_path('Models/' . $modelName . '.php');
        File::put($path, $content);
    }

    private function getRelationshipType($type)
    {
        $hashCount = substr_count($type, '#');
        switch ($hashCount) {
            case 1:
                return 'belongsTo';
            case 2:
                return 'hasOne';
            case 3:
                return 'hasMany';
            case 4:
                return 'belongsToMany';
            default:
                return 'belongsTo';
        }
    }

    private function generateRelationships($relationships)
    {
        $relationshipMethods = '';

        foreach ($relationships as $relationship) {
            $method = $relationship['method'];
            $type = $relationship['type'];
            $model = $relationship['model'];

            $relationshipMethods .= "\n";
            $relationshipMethods .= "    public function $method()\n";
            $relationshipMethods .= "    {\n";
            $relationshipMethods .= "        return \$this->$type($model::class);\n";
            $relationshipMethods .= "    }\n";
        }

        return $relationshipMethods;
    }
}
