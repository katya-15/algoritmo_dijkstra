<!DOCTYPE html>
<html lang="en" data-theme="dim">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>laravel</title>

    <!-- Fonts -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="flex flex-col h-screen justify-between ">
    <header>
        <div class="navbar bg-base-100 shadow-sm">
            <div class="navbar-start">
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                    </div>
                    <ul tabindex="0"
                        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                        <li><a href="{{ route('ciudades.index') }}"><i class="fas fa-city mr-2"></i> Ciudades</a></li>
                        <li><a href="{{ route('rutas.index') }}"><i class="fas fa-route mr-2"></i> Rutas</a></li>
                    </ul>
                </div>
            </div>
            <div class="navbar-center">
                <a class="btn btn-ghost text-xl" href="{{ route('admin.dashboard') }}">Rutas Tezulutlán</a>
            </div>
            <div class="navbar-end">
            </div>
        </div>
    </header>


    <main class="flex-grow container mx-auto px-4 py-6">
        {{ $slot }}
    </main>





    <footer class="footer sm:footer-horizontal bg-neutral text-neutral-content p-10">
        <aside>
            <i class="fa-solid fa-route fa-4x"></i>
            <p>
                Rutas Tezulutlán
                <br />
                Ofrecioendo rutas optimas desde 2010
            </p>
        </aside>
        <nav>
            <h6 class="footer-title">Contacto</h6>
            <div class="grid grid-flow-col gap-4">
                <a href="https://github.com/jakeipe" target="_blank">
                    <i class="fa-brands fa-square-github fa-3x"></i>
                </a>
            </div>
        </nav>
    </footer>

</body>

</html>
