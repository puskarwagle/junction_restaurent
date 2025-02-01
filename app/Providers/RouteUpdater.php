<?php
namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Log;

class RouteUpdater
{
    /**
     * Add a new route to web.php.
     *
     * @param string $model
     * @param string $controllerClass
     * @return int
     */
    public function addRoute($model, $controllerClass)
    {
        $webFilePath = base_path('routes/web.php');

        if (!File::exists($webFilePath)) {
            throw new Exception("web.php file not found at $webFilePath");
        }

        // Generate the route name and URI
        $routeName = Str::snake(Str::plural($model)); // e.g., "test_cruds"
        $routeUri = Str::plural($routeName); // e.g., "test_cruds"

        // Extract the short class name (e.g., "TestCrudController")
        $controllerClassName = class_basename($controllerClass);

        // New route to add
        $newRoute = <<<EOD
        
        
Route::get('$routeUri', $controllerClassName::class)
    ->middleware(['auth'])
    ->name('$routeName');
EOD;

        // Read the existing content
        $content = File::get($webFilePath);

        // Check if the route already exists
        $routeExists = Str::contains($content, "Route::get('$routeUri'");

        // Check if the use statement already exists
        $useStatement = "use $controllerClass;";
        $useExists = Str::contains($content, $useStatement);

        // Determine the return value based on the presence of the route and use statement
        if ($routeExists && $useExists) {
            Log::info("Both route for $routeName and use statement for $controllerClass already exist in web.php.");
            return 0;
        } elseif ($routeExists || $useExists) {
            Log::info("Either route for $routeName or use statement for $controllerClass already exists in web.php.");
            return 1;
        }

        // If neither exists, add both the route and the use statement
        $content = $this->addUseStatement($content, $useStatement);
        $content = $this->addRouteToContent($content, $newRoute);

        // Write the updated content back to the file
        File::put($webFilePath, $content);

        Log::info("Route for $routeName and use statement for $controllerClass added to web.php.");
        return 2;
    }

    /**
     * Add the use statement to the content if it doesn't already exist.
     *
     * @param string $content
     * @param string $useStatement
     * @return string
     */
    protected function addUseStatement($content, $useStatement)
    {
        if (!Str::contains($content, $useStatement)) {
            $lastUsePos = strrpos($content, 'use App\Livewire');

            if ($lastUsePos === false) {
                // If no use statement for Livewire exists, add it at the top
                $content = "<?php\n\n$useStatement\n\n" . ltrim($content, "<?php\n");
            } else {
                // Find the end of the last use statement line
                $lastUseEndPos = strpos($content, "\n", $lastUsePos);

                // Insert the new use statement after the last use statement
                $content = substr_replace(
                    $content,
                    "\n" . $useStatement,
                    $lastUseEndPos + 1,
                    0
                );
            }
        }

        return $content;
    }

    /**
     * Add the route to the content.
     *
     * @param string $content
     * @param string $newRoute
     * @return string
     */
    protected function addRouteToContent($content, $newRoute)
    {
        // Find the last occurrence of "Route::get" that is not commented out
        $lastRouteGetPos = $this->findLastRouteGet($content);

        if ($lastRouteGetPos === false) {
            throw new Exception("No valid Route::get found in web.php.");
        }

        // Find the end of the last Route::get (look for the semicolon)
        $lastRouteGetEndPos = strpos($content, ';', $lastRouteGetPos);

        if ($lastRouteGetEndPos === false) {
            throw new Exception("Invalid Route::get syntax in web.php.");
        }

        // Insert the new route after the last Route::get
        return substr_replace(
            $content,
            "\n" . $newRoute,
            $lastRouteGetEndPos + 1,
            0
        );
    }

    /**
     * Find the last occurrence of "Route::get" that is not commented out.
     *
     * @param string $content
     * @return int|false
     */
    protected function findLastRouteGet($content)
    {
        $lastRouteGetPos = false;
        $pos = 0;

        while (($pos = strpos($content, 'Route::get', $pos)) !== false) {
            // Check if the line is commented out
            $lineStart = strrpos(substr($content, 0, $pos), "\n") + 1;
            $line = substr($content, $lineStart, $pos - $lineStart);

            if (!Str::startsWith(trim($line), '//')) {
                $lastRouteGetPos = $pos;
            }

            $pos += strlen('Route::get');
        }

        return $lastRouteGetPos;
    }
}