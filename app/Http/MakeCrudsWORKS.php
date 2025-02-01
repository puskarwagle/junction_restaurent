<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Providers\CrudControllerGenerator;
use App\Providers\CrudViewGenerator;
use App\Providers\NavigationUpdater;
use App\Providers\RouteGenerator;

use Exception;
use Illuminate\Support\Facades\Log;

class MakeCruds extends Command
{
    protected $signature = 'make:cruds';
    protected $description = 'Generate Livewire 3 CRUD with controllers and views';

    // Test values for quick testing
    protected $testModel = 'TestCrud';
    protected $testControllerPath = 'app/Livewire/TestCrud';
    protected $testViewsPath = 'resources/views';

    public function handle()
    {
        $this->info('Hey pyaro manis!');
    
        // Use test values for quick testing
        $models = [$this->testModel];
        $model = $this->testModel;
        $controllerPath = $this->testControllerPath;
        $controllerClass = str_replace('/', '\\', ucfirst($controllerPath)) . '\\' . $model . 'Controller';
        
        $viewsPath = $this->testViewsPath;
    
        try {
            // Step 1: Generate Controller
            $this->info("Step 1: Generating controller for $model...");
            $controllerGenerator = new CrudControllerGenerator();
            $controllerGenerator->generate($model, $controllerPath);
            $this->info("✅ Controller generated successfully for $model.");
    
            // Step 2: Generate View
            $this->info("Step 2: Generating view for $model...");
            $viewGenerator = new CrudViewGenerator();
            $viewGenerator->generate($model, $viewsPath);
            $this->info("✅ View generated successfully for $model.");
    
            // Step 3: Update Navigation
            $this->info("Step 3: Updating navigation for $model...");
            $navigationUpdater = new NavigationUpdater();
            $navigationUpdater->update($model, $viewsPath);
            $this->info("✅ Navigation updated successfully for $model.");
    
            // Step 4: Add Route to web.php
            $this->info("Step 4: Adding route for $model to web.php...");
            $routeGenerator = new RouteGenerator();
            $routeGenerator->addRoute($model, $controllerClass);
            $this->info("✅ Route added successfully for $model.");
    
            // Final Success Message
            $this->info("🎉 CRUD generation completed for $model.");
        } catch (Exception $e) {
            // Handle any errors that occur during the process
            $this->error("❌ An error occurred: " . $e->getMessage());
            Log::error("Error in MakeCruds command: " . $e->getMessage());
        }
    }
}