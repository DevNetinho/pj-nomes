<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>@yield('titulo', 'Titulo padrão')</title>
</head>
<body>
    <div id="app">
                    
        @yield('conteudo')
        
    </div>
    
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>