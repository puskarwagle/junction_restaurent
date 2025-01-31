<?php
namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CrudViewGenerator
{
    public function generate($model, $viewsPath)
    {
        // $modelkebapi = Str::kebab(Str::plural($model));
        $viewFile = "$viewsPath/{$model}-cruds.blade.php";

        // Ensure the directory exists
        File::ensureDirectoryExists($viewsPath);

        // Check if the view file already exists
        if (File::exists($viewFile)) {
            return;
        }

        // Generate the view content
        File::put($viewFile, <<<EOD
        <h1>Hey pyaromanis</h1>
    EOD);

        Log::info("View generated for $model at $viewFile");
    }
}