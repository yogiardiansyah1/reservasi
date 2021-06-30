<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>{{config('app.name', 'Restoran')}}</title>
</head>
<body>
    <div class="header">
        <h1>Logo</h1>
        <div class="header-right">
            <a href="/reservasi">Reservasi</a>
        </div>
    </div>
    <div class="main">
        @yield('content')
    </div>
    <div class="footer">
        <h1>ini footer</h1>
    </div>
</body>
</html>