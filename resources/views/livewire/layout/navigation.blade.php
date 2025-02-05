<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>


<nav x-data="{ open: false }" class="nav-container">
    <div class="nav-flex-container">
        <div class="sidebar">
            <div class="w-100 bg-gray-800">
                <!-- Navigation Links -->
                <div class="hidden sm:-my-px sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

                <div class="hidden sm:-my-px sm:flex">
                    <x-nav-link :href="route('users')" :active="request()->routeIs('users')" wire:navigate>
                        {{ __('Users') }}
                    </x-nav-link>
                </div>

<div class="hidden sm:-my-px sm:flex">
    <x-nav-link :href="route('coupon_codes')" :active="request()->routeIs('coupon_codes')" wire:navigate>
        {{ __('CouponCodes') }}
    </x-nav-link>
</div>


<div class="hidden sm:-my-px sm:flex">
    <x-nav-link :href="route('order_items')" :active="request()->routeIs('order_items')" wire:navigate>
        {{ __('OrderItems') }}
    </x-nav-link>
</div>


<div class="hidden sm:-my-px sm:flex">
    <x-nav-link :href="route('menu_items')" :active="request()->routeIs('menu_items')" wire:navigate>
        {{ __('MenuItems') }}
    </x-nav-link>
</div>


<div class="hidden sm:-my-px sm:flex">
    <x-nav-link :href="route('post_offices')" :active="request()->routeIs('post_offices')" wire:navigate>
        {{ __('PostOffices') }}
    </x-nav-link>
</div>


<div class="hidden sm:-my-px sm:flex">
    <x-nav-link :href="route('site_settings')" :active="request()->routeIs('site_settings')" wire:navigate>
        {{ __('SiteSettings') }}
    </x-nav-link>
</div>





            



            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('coupon_codes')" :active="request()->routeIs('coupon_codes')" wire:navigate>
        {{ __('CouponCodes') }}
    </x-responsive-nav-link>
</div>


<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('order_items')" :active="request()->routeIs('order_items')" wire:navigate>
        {{ __('OrderItems') }}
    </x-responsive-nav-link>
</div>


<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('menu_items')" :active="request()->routeIs('menu_items')" wire:navigate>
        {{ __('MenuItems') }}
    </x-responsive-nav-link>
</div>


<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('post_offices')" :active="request()->routeIs('post_offices')" wire:navigate>
        {{ __('PostOffices') }}
    </x-responsive-nav-link>
</div>


<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('site_settings')" :active="request()->routeIs('site_settings')" wire:navigate>
        {{ __('SiteSettings') }}
    </x-responsive-nav-link>
</div>






        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200"
                    x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                    x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
