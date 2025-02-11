<?php
namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class CrudViewGenerator
{
    /**
     * Generate a CRUD view for the given model.
     */
    public function generate(string $model, string $viewsPath): bool
    {
        $viewFile = "$viewsPath/{$model}-cruds.blade.php";

        // Ensure the directory exists
        File::ensureDirectoryExists($viewsPath);

        // Load the stub file
        $stubPath = base_path('app/stubs/crud_view.stub'); // Adjust path if needed
        $stubContent = File::get($stubPath);

        // Replace the placeholders in the stub
        $replacedContent = str_replace([
            '{{ model }}',
        ], [
            $model,
        ], $stubContent);

        // Write the file
        File::put($viewFile, $replacedContent);

        Log::info("View generated for $model at $viewFile");
        return true;
    }
}