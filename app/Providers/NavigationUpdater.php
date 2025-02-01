<?php
namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class NavigationUpdater
{
    public function update($model, $viewsPath)
    {
        $navFilePath = "$viewsPath/livewire/layout/navigation.blade.php";
        $routeName = Str::snake(Str::plural($model));
        $routeLabel = Str::plural($model);

        if (!File::exists($navFilePath)) {
            Log::warning("Navigation file not found at $navFilePath.");
            return 0;
        }

        $content = File::get($navFilePath);

        if (Str::contains($content, "route('$routeName')")) {
            Log::info("Navigation link for $routeLabel already exists.");
            return 0;
        }

        $desktopRouteLink = "\n<div class=\"hidden space-x-8 sm:-my-px sm:ms-10 sm:flex\">\n"
            . "    <x-nav-link :href=\"route('$routeName')\" :active=\"request()->routeIs('$routeName')\" wire:navigate>\n"
            . "        {{ __('$routeLabel') }}\n"
            . "    </x-nav-link>\n"
            . "</div>\n";

        $responsiveRouteLink = "\n<div class=\"pt-2 pb-3 space-y-1\">\n"
            . "    <x-responsive-nav-link :href=\"route('$routeName')\" :active=\"request()->routeIs('$routeName')\" wire:navigate>\n"
            . "        {{ __('$routeLabel') }}\n"
            . "    </x-responsive-nav-link>\n"
            . "</div>\n";

        // Insert desktop link after the last occurrence of the desktop navigation section
        $content = preg_replace('/(<\/x-nav-link>\s*<\/div>)(?![\s\S]*\1)/', "$1$desktopRouteLink", $content, 1);

        // Insert responsive link after the last occurrence of the responsive navigation section
        $content = preg_replace('/(<\/x-responsive-nav-link>\s*<\/div>)(?![\s\S]*\1)/', "$1$responsiveRouteLink", $content, 1);

        File::put($navFilePath, $content);
        Log::info("Navigation file updated with route for $routeLabel.");
        return 1;
    }
}