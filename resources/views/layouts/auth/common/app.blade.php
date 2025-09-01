<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ahlinya Retail</title>
    @include('layouts.auth.common.components.style')
    @stack('css')
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        @yield('content')
    </div>

    @include('layouts.auth.common.components.script')
    @stack('js')
</body>
</html>
