<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="keywords" content="Binco Talent" />
        <meta name="description" content="asosiasi pengemudi ojek online" />
        <meta name="author" content="Binco Talent" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="index, follow">
        <meta name="copyright" content="Copyright © 2025 • All rights reserved • Lingkar Rasio Teknologi">

        <meta property="og:title" content="Binco Talent">
        <meta property="og:description" content="Binco Talent">
        <title>
            Binco Talent
        </title>

        @include('layouts.e-commerce.components.style')

    </head>
    <body>
        <div id="layoutDefault">
            <div id="layoutDefault_content">
                @include('layouts.e-commerce.components.navbar')

                <main>
                    @yield('content')
                </main>
            </div>
        </div>

        @include('layouts.e-commerce.components.script')
    </body>
</html>
