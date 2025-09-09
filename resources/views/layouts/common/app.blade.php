<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ahlinya Retail - Toko Fashion</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    @stack('css')
</head>
<body>

    {{-- Navbar --}}
    @include('layouts.partials.navbar')

    {{-- Konten Utama --}}
    @yield('content')

    {{-- Footer --}}
    @include('layouts.partials.footer')

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script Global (AdminLTE, jQuery, SweetAlert, dll) --}}
    @include('layouts.admin.components.script')

    {{-- Script tambahan khusus per halaman --}}
    @stack('js')
</body>
</html>
