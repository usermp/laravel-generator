<?php

namespace Usermp\LaravelGenerator\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RouteGenerator
{
    public function generateRoute($service)
    {
        $name = $service['name'];
        $route = Str::plural(strtolower($name));
    
        $routeDefinition = "\n\nRoute::apiResource('{$route}', '\App\Http\Controllers\{$name}Controller');";
        // Define the path to the routes file
        $path = base_path('routes/api.php');

        // Append the new route to the routes file
        file_put_contents($path, $routeDefinition, FILE_APPEND);

    }
}
