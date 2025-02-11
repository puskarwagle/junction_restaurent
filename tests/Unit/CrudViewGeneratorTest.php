<?php

use App\Providers\CrudViewGenerator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

uses(Tests\TestCase::class);

it('generates a CRUD view file from a stub', function () {
    // Arrange
    $model = 'TestModel';
    $viewsPath = 'tests/tmp/views';  // Temporary directory for testing
    $viewFile = $viewsPath . '/' . $model . '-cruds.blade.php';

    File::deleteDirectory($viewsPath); // Start with a clean directory

    // Create the views directory
    File::ensureDirectoryExists($viewsPath);

    // Create a mock view.stub file
    $stubPath = base_path('app/stubs/crud-view.stub');
    $stubContent = '<h1>{{ model }} CRUD View</h1>'; // Basic stub content
    File::put($stubPath, $stubContent);

    $generator = new CrudViewGenerator();

    // Act
    $result = $generator->generate($model, $viewsPath);

    // Assert
    expect($result)->toBeTrue();
    expect(File::exists($viewFile))->toBeTrue();
    expect(File::get($viewFile))->toContain('<h1>TestModel CRUD View</h1>'); // Ensure stub content is merged correctly

    // Cleanup
    File::deleteDirectory($viewsPath);
    File::delete($stubPath); // Delete the mock stub file
});

it('logs an info message after successful generation', function () {
    // Arrange
    $model = 'TestModel';
    $viewsPath = 'tests/tmp/views';
    $viewFile = $viewsPath . '/' . $model . '-cruds.blade.php';

    File::ensureDirectoryExists($viewsPath);

    $stubPath = base_path('app/stubs/crud-view.stub');
    $stubContent = '<h1>{{ model }} CRUD View</h1>';
    File::put($stubPath, $stubContent);
    $generator = new CrudViewGenerator();

    Log::shouldReceive('info')
        ->once()
        ->with("View generated for $model at $viewFile");

    // Act
    $generator->generate($model, $viewsPath);

    // Cleanup
    File::deleteDirectory($viewsPath);
    File::delete($stubPath);
});

it('ensures the views directory exists before generating the view', function() {
    // Arrange
    $model = 'TestModel';
    $viewsPath = 'tests/tmp/views';
    $viewFile = $viewsPath . '/' . $model . '-cruds.blade.php';

    File::deleteDirectory($viewsPath);

    $stubPath = base_path('app/stubs/crud-view.stub');
    $stubContent = '<h1>{{ model }} CRUD View</h1>';
    File::put($stubPath, $stubContent);

    $generator = new CrudViewGenerator();

    // Act
    $generator->generate($model, $viewsPath);

    // Assert
    expect(File::exists($viewsPath))->toBeTrue();

    // Cleanup
    File::deleteDirectory($viewsPath);
    File::delete($stubPath);
});