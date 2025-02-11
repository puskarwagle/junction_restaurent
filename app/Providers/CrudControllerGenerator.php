<?php
namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Log;

class CrudControllerGenerator
{
    public function generate(string $model, string $controllerPath): void
    {
        $controllerClass = $model . 'Controller';
        $controllerFile = "$controllerPath/$controllerClass.php";

        $namespaceForThisFile = implode('\\', array_map(fn($part) => Str::studly($part), explode('/', $controllerPath)));
        // $namespaceForThisFile = 'namespace App\Livewire\Backend';
        $modelImportPath = "App\\Models\\$model";

        if (!class_exists($modelImportPath)) {
            throw new Exception("Model $modelImportPath not found.");
        }

        $fillable = (new $modelImportPath)->getFillable();
        $properties = implode("\n    ", array_map(fn($f) => "public \$$f;", $fillable));

        $rulesFile = base_path('app/Providers/marules.json');
        $rulesData = json_decode(file_get_contents($rulesFile), true);
        $rulesArray = $rulesData[$controllerClass]['rules'] ?? [];
        $rulesString = implode(",\n        ", array_map(fn($k, $v) => "'$k' => '$v'", array_keys($rulesArray), $rulesArray));

        $viewName = $model;
        $layoutName = 'layouts.app';

        // Load the stub file
        $stubPath = base_path('app/stubs/crud_controller.stub'); // Adjust path if needed
        $stubContent = File::get($stubPath);

        // Replace the placeholders in the stub
        $replacedContent = str_replace([
            '{{ namespace }}',
            '{{ modelImportPath }}',
            '{{ controllerClass }}',
            '{{ properties }}',
            '{{ rulesString }}',
            '{{ viewName }}',
            '{{ layoutName }}',
            '{{ model }}',
        ], [
            $namespaceForThisFile,
            $modelImportPath,
            $controllerClass,
            $properties,
            $rulesString,
            $viewName,
            $layoutName,
            $model,
        ], $stubContent);

        File::ensureDirectoryExists($controllerPath);
        File::put($controllerFile, $replacedContent);

        Log::info("Controller generated for $model at $controllerFile");
    }

}