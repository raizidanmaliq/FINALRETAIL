{{-- Start Style Css --}}
<link
rel="icon"
type="image/x-icon"
href="{{ asset('images/home/logo.png') }}"
/>
<link href="{{ mix('css/styles.css') }}" rel="stylesheet" />
<link href="{{ mix('css/font.css') }}" rel="stylesheet" />
<link href="{{ mix('css/color.css') }}" rel="stylesheet" />
<link href="{{ mix('css/index.css') }}" rel="stylesheet" />
{{-- End Style Css --}}

<link rel="stylesheet" href="{{ asset('/back_assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('/back_assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
{{-- End Font Awesome --}}

{{-- Font Declaration --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Yeseva+One&display=swap" rel="stylesheet">

@stack('css')
