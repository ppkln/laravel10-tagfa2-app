<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TAGFA-TH') }}</title>
        <link rel="icon" type="image/jpg" sizes="20x20" href="/TAGFA_icon.jpg"/>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts CDN Bootstrap 5 -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <!-- Scripts CDN Bootstrap 5 -->

        <!-- fancybox Gallery โดยผู้พัฒนา -->
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
        />
        <!-- End fancybox Gallery-->



        <!-- Styles -->
        @livewireStyles
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <!-- Gallery script โดยผู้พัฒนา-->
        <style >
        *{
            margin:0;
            padding:0;
            box-sizing: border-box;
        }
        .container-album {
            max-width:1028px;
            margin:0 auto;
        }
        .products-con{
            display:grid;
            grid-template-columns: repeat(auto-fill,minmax(180px,1fr));
            grid-gap:0.5rem;
        }
        .products-item {
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
            transition:0.3s;
        }
        .products-item:hover {
            border: 1px solid orange;
        }
        .products-detail {
            padding:0.5rem;
        }
        .products-img img{
            width:100%;
        }
        .products-price {
            padding: 1rem;
            align-items: center;
            justify-content: space-between;
        }
        .products-left span{
            font-size: 6px;
        }
        .products-right span{
            font-size: 6px;
        }


        .posts-con{
            display:grid;
            grid-template-columns: repeat(auto-fill,minmax(180px,1fr));
            grid-gap:0.5rem;
        }
        .posts-item {
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
            transition:0.3s;
        }
        .posts-item:hover {
            border: 1px solid orange;
        }
        .posts-detail {
            padding:0.5rem;
        }
        .posts-img img{
            width:100%;
        }
        .posts-price {
            padding: 1rem;
            align-items: center;
            justify-content: space-between;
        }
        .posts-left span{
            font-size: 6px;
        }
        .posts-right span{
            font-size: 6px;
        }
        </style>


    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts


    </body>
</html>
