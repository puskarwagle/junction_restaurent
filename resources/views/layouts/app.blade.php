<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravelk') }}</title>

        <!--=== Link Of CSS Fils ===-->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/animate.min.css">
        <link rel="stylesheet" href="assets/css/boxicons.min.css">
        <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
        <link rel="stylesheet" href="assets/css/meanmenu.css">
        <link rel="stylesheet" href="assets/css/fancybox.min.css">
        <link rel="stylesheet" href="assets/css/odometer.min.css">
        <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
        <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
        <link rel="stylesheet" href="assets/css/scrollCue.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/dark.css">
        <link rel="stylesheet" href="assets/css/responsive.css">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border:0px solid red;
                padding: 1rem 3rem;
                background-color: rgb(17 24 39);
            }
            
            /* Full height and flexible layout for the nav */
            .nav-flex-container {
                display: flex;
                flex-direction: column;
                align-items:center;
                border:0px solid orange;
                width:100%;

            }
            .blendInputs[type=text] {
                border: none !important; /* Remove borders */
                background: transparent !important; /* Transparent background */
                outline: none !important; /* Remove focus outline */
                padding: 0px; /* Remove padding */
                border-radius: 4px; /* Slight rounding */
                width: 100%; /* Ensure full width */
                min-width: 100%; /* Prevent truncation */
                vertical-align: middle; /* Align with table text */
                margin: 0; /* Remove unwanted spacing */
                box-shadow: none !important; /* Remove shadow */
            }

            .btn-primary, .btn-primary:active, .btn-primary:visited {
                background-color:rgb(4, 114, 37) !important;;
                border-color: rgb(5, 98, 33) !important;; 
            }
            .btn-primary:hover{
                background-color:rgb(5, 86, 29) !important;
                border-color: rgb(5, 98, 33) !important;
                color: #ffffff;
            }
            .btn-primary:active, .btn-primary:visited {
                background-color:rgb(5, 86, 29) !important;
                border-color: #ffffff !important;
                color: #ffffff;
            }
            
            .darr{
                background-color:rgb(98, 5, 34);
                border-color: rgb(98, 5, 34);
                color: #ffffff;
            }
            .darr:hover{
                background-color:rgb(72, 8, 28);
                border-color: rgb(72, 8, 28);
            }
            
            .darr:focus {
                background-color:rgb(72, 8, 28);
                border-color: rgb(72, 8, 28);
            }
            .blendInputs:focus {
                border: 1px solid #ddd !important; /* Optional: Add a subtle border on focus */
            }

            .sidebar {
                
                display: flex;
                flex-direction: column;
                color: white;
                outline:0px solid orange;
                width:100%;

            }
            .sidebar div {
                background-color: rgb(17 24 39);
                width:100%;

                /* background-color: rgb(79 70 229); */
                
            }
            .sidebar a {
                padding:1rem 4rem 0.5rem 2rem !important;
                font-size: 1.2rem;
                line-height: 2.5rem;
                outline:0px solid orange;
                width:100%;
            }
            .sidebar a:hover {
                background-color: rgb(55 65 81);
                /* background-color:#312e8180; */
            }
            /* .sidebar a:active {
                background-color:#312e8180;
                font-size:2rem;
            } */
            .outbdr {
                border:0px solid red;
                background-color: rgb(17 24 39);
                padding-top:1rem;
            }
            .nav-container {
                display: flex;
                border:0px solid blue;
                flex: 0 0 15%;  
            }
            main {
                outline: 0px solid red;
                flex: 0 0 85%;
            }
            main table td {
                /* padding: 0 !important; */
                margin: 0;
            }
            main table td input[type=text] {
                border: none !important; /* Remove borders */
                background: transparent !important; /* Transparent background */
                outline: none !important; /* Remove focus outline */
                padding: 0px; /* Remove padding */
                border-radius: 4px; /* Slight rounding */
                width: 100%; /* Ensure full width */
                min-width: 100%; /* Prevent truncation */
                vertical-align: middle; /* Align with table text */
                margin: 0; /* Remove unwanted spacing */
                box-shadow: none !important; /* Remove shadow */
            }
        </style>
    </head>
    <body class="font-sans antialiased">
    <livewire:layout.header />
        <div class="flex min-h-screen min-w-screen outbdr">
            <livewire:layout.navigation />
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>