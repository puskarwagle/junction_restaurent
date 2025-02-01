<?php
namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Log;

class NavigationUpdater
{
    public function update($model, $viewsPath)
    {
        $navFilePath = "$viewsPath/livewire/layout/navigation.blade.php";
        $routeName = Str::snake(Str::plural($model));
        $routeLabel = Str::plural($model);

        $desktopRouteLink = <<<EOD


                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('$routeName')" :active="request()->routeIs('$routeName')" wire:navigate>
                                {{ __('$routeLabel') }}
                            </x-nav-link>
                        </div>
        EOD;

        $responsiveRouteLink = <<<EOD


                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('$routeName')" :active="request()->routeIs('$routeName')" wire:navigate>
                        {{ __('$routeLabel') }}
                    </x-responsive-nav-link>
                </div>
        EOD;

        if (!File::exists($navFilePath)) {
            throw new Exception("Navigation file not found at $navFilePath");
        }

        $content = File::get($navFilePath);

        // Check if the desktop navigation link already exists
        if (Str::contains($content, "route('$routeName')")) {
            Log::info("Navigation link for $routeLabel already exists in navigation.blade.php.");
            return;
        }

        // Add desktop navigation link
        $lastDesktopNavLink = strrpos($content, '</x-nav-link>');
        if ($lastDesktopNavLink !== false) {
            $desktopDivStart = strrpos($content, '<div', $lastDesktopNavLink - strlen($content));
            $desktopDivEnd = strpos($content, '</div>', $lastDesktopNavLink);

            if ($desktopDivStart !== false && $desktopDivEnd !== false) {
                $content = substr_replace(
                    $content,
                    $desktopRouteLink,
                    $desktopDivEnd + strlen('</div>'),
                    0
                );
            }
        }

        // Add responsive navigation link
        $responsiveNavLinkPattern = '/<x-responsive-nav-link([^>]*:active="[^"]*"[^>]*)>/s';
        preg_match_all($responsiveNavLinkPattern, $content, $matches);

        if (!empty($matches[0])) {
            $lastResponsiveNavLink = end($matches[0]);
            $lastResponsiveNavLinkPos = strrpos($content, $lastResponsiveNavLink);

            if ($lastResponsiveNavLinkPos !== false) {
                $responsiveDivStart = strrpos($content, '<div', $lastResponsiveNavLinkPos - strlen($content));
                $responsiveDivEnd = strpos($content, '</div>', $lastResponsiveNavLinkPos);

                if ($responsiveDivStart !== false && $responsiveDivEnd !== false) {
                    $content = substr_replace(
                        $content,
                        $responsiveRouteLink,
                        $responsiveDivEnd + strlen('</div>'),
                        0
                    );
                }
            }
        }

        File::put($navFilePath, $content);
        Log::info("Navigation file updated with route for $routeLabel.");
    }
}