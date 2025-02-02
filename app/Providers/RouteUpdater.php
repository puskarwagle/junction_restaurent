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

        // Check if the route and use statement already exist
        $routeExists = Str::contains($content, "Route::get('$routeUri'");
        $useStatementExists = Str::contains($content, "use $controllerClass;");

        if ($routeExists && $useStatementExists) {
            Log::info("Route and use statement for $routeName already exist in web.php.");
            return 0;
        }

        // Add the use statement if it doesn't exist
        if (!$useStatementExists) {
            $content = $this->insertUseStatement($content, $controllerClass);
            Log::info("Use statement for $controllerClass added to web.php.");
        }

        // Add the route if it doesn't exist
        if (!$routeExists) {
            $content = $this->insertRoute($content, $newRoute);
            Log::info("Route for $routeName added to web.php.");
        }

        // Write the updated content back to the file
        File::put($webFilePath, $content);

        return 1;
    }

    /**
     * Insert a use statement into the content.
     *
     * @param string $content
     * @param string $controllerClass
     * @return string
     */
    protected function insertUseStatement($content, $controllerClass)
    {
        $useStatement = "use $controllerClass;";

        // Insert the use statement after the opening PHP tag or the last use statement
        if (strpos($content, '<?php') === 0) {
            $content = substr_replace($content, "<?php\n$useStatement\n", 0, 5);
        } else {
            $lastUsePos = strrpos($content, 'use ');
            if ($lastUsePos !== false) {
                $lastUseEndPos = strpos($content, "\n", $lastUsePos);
                $content = substr_replace($content, "\n$useStatement", $lastUseEndPos, 0);
            } else {
                $content = "<?php\n$useStatement\n\n" . ltrim($content, "<?php\n");
            }
        }

        return $content;
    }

    /**
     * Insert a route into the content.
     *
     * @param string $content
     * @param string $newRoute
     * @return string
     */
    protected function insertRoute($content, $newRoute)
    {
        // Find the last occurrence of "Route::"
        $lastRoutePos = strrpos($content, 'Route::');

        if ($lastRoutePos === false) {
            // If no routes exist, append the new route at the end
            return $content . "\n" . $newRoute;
        }

        // Find the end of the last route (semicolon)
        $lastRouteEndPos = strpos($content, ';', $lastRoutePos);

        if ($lastRouteEndPos === false) {
            throw new Exception("Invalid route syntax in web.php.");
        }

        // Insert the new route after the last route
        return substr_replace(
            $content,
            "\n" . $newRoute,
            $lastRouteEndPos + 1,
            0
        );
    }
}