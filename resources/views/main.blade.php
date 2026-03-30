<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sales ETL Analytics')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    @vite(['resources/css/app.css'])
    @if(Request::is('imports/create'))
        @vite(['resources/css/imports/create.css'])
    @elseif(Request::is('imports/*'))
        @vite(['resources/css/imports/show.css'])
    @else
        @vite(['resources/css/imports/index.css'])
    @endif
</head>
<body>
    <div class="nav-menu">
        <div class="nav-item">
            <a href="{{ route('imports.create') }}">Importar CSV</a>
        </div>
        <div class="nav-item">
            <a href="{{ route('imports.index.web') }}">Historial de importaciones</a>
        </div>
    </div>

    <div class="main-container">
        @yield('content')
    </div>

    @routes(['imports.store'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ziggy@2.4.0/bin/ziggy.min.js"></script>
    @vite(['resources/js/app.js'])

    <script>
        let page = 'index';
        @if(Request::is('imports/create'))
            page = 'create';
        @endif
    </script>
</body>
</html>