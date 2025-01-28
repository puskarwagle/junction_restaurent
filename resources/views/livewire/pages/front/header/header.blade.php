<header>
    <!-- Start Dark Mode -->
    <div class="switch-theme-mode">
        <label id="switch" class="switch">
            <input type="checkbox" onchange="toggleTheme()" id="slider">
            <span class="slider round"></span>
        </label>
    </div>
    <!-- End Dark Mode -->

    <!-- Start Menubar Area -->
    <div class="navbar-area">
        <!-- Start Menu For Mobile Device -->
        <div class="container">
            <div class="mobile-nav">
                <div class="logo">
                    <a href="/" wire:navigate.hover>
                        <img src="assets/images/header/logo.jpeg" class="logo-light" alt="images">
                    </a>
                </div>
            </div>
        </div>
        <!-- End Menu For Mobile Device -->

        <div class="main-nav">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-md navbar-light">

                    <a href="/" wire:navigate.hover>
                        <img src="assets/images/header/logo.jpeg" class="logo-light" alt="images">
                    </a>

                    <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">
                            <!-- <li class="nav-item nav-item-five">
                                <button onclick="resetSessionStorage()"
                                    style="background-color: #ff4444; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 14px;">
                                    Reset Session Storage (Dev)
                                </button>

                                <script>
                                    function resetSessionStorage() {
                                        // Confirm before resetting
                                        if (confirm(
                                                'Are you sure you want to reset session storage? This cannot be undone.'
                                            )) {
                                            sessionStorage.clear(); // Clear session storage
                                            alert('Session storage has been reset!');
                                            window.location.reload(); // Reload the page to reflect changes
                                        }
                                    }

                                </script>
                            </li> -->
                            <li class="nav-item nav-item-five">
                                <a href="/" wire:navigate.hover class="nav-link dropdown-toggle active">Home</a>
                            </li>

                            <li class="nav-item nav-item-five">
                                <a href="/about" wire:navigate.hover class="nav-link dropdown-toggle">About</a>
                            </li>

                            <li class="nav-item nav-item-five">
                                <a href="/menu" wire:navigate.hover class="nav-link dropdown-toggle">Menu</a>
                            </li>

                            <li class="nav-item">
                                <a href="/contact" wire:navigate.hover class="nav-link dropdown-toggle">Contact Us</a>
                            </li>
                        </ul>


                        <div class="others-option-vg d-flex align-items-center">
                            <div class="option-item">
                                <a href="/cart" wire:navigate.hover>
                                    <div class="shapping-bag">
                                        <img src="assets/images/header/shopping-bag-icon.svg" alt="images">
                                        <div class="shapping-text">
                                            {{ $cartCount }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="option-item">
                                <a href="/bookaTable" wire:navigate.hover class="default-btn">Book A Tables</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <div class="others-option-for-responsive">
            <div class="container">
                <div class="dot-menu">
                    <div class="inner">
                        <div class="circle circle-one"></div>
                        <div class="circle circle-two"></div>
                        <div class="circle circle-three"></div>
                    </div>
                </div>

                <div class="container">
                    <div class="option-inner">
                        <div class="others-option justify-content-center d-flex align-items-center">

                            <div class="option-item">
                                <i class='bx bx-search search-btn'></i>
                                <i class='bx bx-x close-btn'></i>
                                <div class="search-overlay search-popup">
                                    <div class='search-box'>
                                        <form class="search-form">
                                            <input class="search-input" placeholder="Search..." type="text">

                                            <button class="search-button" type="submit">
                                                <i class='bx bx-search'></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="option-item">
                                <div class="shapping-bag">
                                    <a href="\cart" wire:navigate.hover>
                                        <img src="assets/images/header/shopping-bag-icon.svg" alt="images">
                                    </a>
                                    <div class="shapping-text">
                                        {{ $cartCount }}
                                    </div>
                                </div>
                            </div>

                            <div class="option-item">
                                <a href="\bookingaTable" class="default-btn">Book A Table</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
