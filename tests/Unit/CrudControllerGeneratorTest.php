<?php

use App\Providers\CrudControllerGenerator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

uses(Tests\TestCase::class);

it('generates a CRUD controller file from a stub', function () {
    // Arrange
    $model = 'TestModel';
    $controllerPath = 'tests/tmp/controllers'; // Temporary path for testing
    $controllerFile = $controllerPath . '/' . $model . 'Controller.php';

    File::deleteDirectory($controllerPath); // Ensure a clean state
    File::ensureDirectoryExists($controllerPath);

    // Create a mock controller.stub
    $stubPath = base_path('app/stubs/crud-controller.stub');
    $stubContent = 'namespace App\Livewire\Backend; class {{ controllerClass }} {}';
    File::put($stubPath, $stubContent);

    // Create a dummy model for getFillable() and check if the Model Exists
    $modelPath = app_path('Models');
    File::ensureDirectoryExists($modelPath);
    $dummyModel = $modelPath . '/TestModel.php';
    $dummyModelContent = "<?php namespace App\Models; use Illuminate\Database\Eloquent\Model; class TestModel extends Model { protected \$fillable = ['name']; }";
    File::put($dummyModel, $dummyModelContent);

    // Create a dummy marules.json for getting the rules
    $rulesPath = app_path('Providers');
    File::ensureDirectoryExists($rulesPath);
    $dummyRules = base_path('app/Providers/marules.json');
    $dummyRulesContent = '{"TestModelController": {"rules": {"name": "required|string|max:255"}}}';
    File::put($dummyRules, $dummyRulesContent);

    $generator = new CrudControllerGenerator();

    // Act
    $generator->generate($model, $controllerPath);

    // Assert
    expect(File::exists($controllerFile))->toBeTrue();
    expect(File::get($controllerFile))->toContain('class TestModelController'); // Check the content
    expect(File::get($controllerFile))->toContain('namespace Tests\Tmp\Controllers;');

    // Cleanup
    File::deleteDirectory($controllerPath);
    File::delete($stubPath);
    File::delete($dummyModel);
    File::delete($dummyRules);
    File::deleteDirectory($modelPath);
    File::deleteDirectory($rulesPath);
});

it('logs an info message after successful controller generation', function () {
    // Arrange
    $model = 'TestModel';
    $controllerPath = 'tests/tmp/controllers';
    $controllerFile = $controllerPath . '/' . $model . 'Controller.php';
    File::ensureDirectoryExists($controllerPath);

    $stubPath = base_path('app/stubs/crud-controller.stub');
    $stubContent = 'namespace App\Livewire\Backend; class {{ controllerClass }} {}';
    File::put($stubPath, $stubContent);

    $modelPath = app_path('Models');
    File::ensureDirectoryExists($modelPath);
    $dummyModel = $modelPath . '/TestModel.php';
    $dummyModelContent = "<?php namespace App\Models; use Illuminate\Database\Eloquent\Model; class TestModel extends Model { protected \$fillable = ['name']; }";
    File::put($dummyModel, $dummyModelContent);

    $rulesPath = app_path('Providers');
    File::ensureDirectoryExists($rulesPath);
    $dummyRules = base_path('app/Providers/marules.json');
    $dummyRulesContent = '{"TestModelController": {"rules": {"name": "required|string|max:255"}}}';
    File::put($dummyRules, $dummyRulesContent);
    $generator = new CrudControllerGenerator();

    Log::shouldReceive('info')
        ->once()
        ->with("Controller generated for $model at $controllerFile");

    // Act
    $generator->generate($model, $controllerPath);

    // Cleanup
    File::deleteDirectory($controllerPath);
    File::delete($stubPath);
    File::delete($dummyModel);
    File::delete($dummyRules);
    File::deleteDirectory($modelPath);
    File::deleteDirectory($rulesPath);
});

it('throws exception if model does not exist', function () {
    // Arrange
    $model = 'NonExistentModel';
    $controllerPath = 'tests/tmp/controllers';

    $generator = new CrudControllerGenerator();

    // Act & Assert
    $this->expectException(Exception::class);
    $this->expectExceptionMessage("Model App\\Models\\NonExistentModel not found.");

    $generator->generate($model, $controllerPath);
});