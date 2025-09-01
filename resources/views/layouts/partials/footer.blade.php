@php
    $settings = \App\Models\Cms\InformationPages::where('slug', 'general-settings')->first();
@endphp

@if ($settings)
    <footer style="background-color:#A34A4A;" class="text-white py-5">

        <div class="container">
            <div class="row text-center text-md-start gy-4">

                {{-- Brand --}}
                <div class="col-md-4">
                    <h4 class="fw-bold mb-3">{{ $settings->company_name }}</h4>
                    <p class="small opacity-75 mb-0">
                        {{ $settings->company_tagline }}
                    </p>
                </div>

                {{-- Kontak --}}
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Kontak Kami</h5>
                    <ul class="list-unstyled small mb-0">
                        @if($settings->whatsapp)
                            <li class="mb-2">
                                <i class="fab fa-whatsapp me-2"></i> {{ $settings->whatsapp }}
                            </li>
                        @endif
                        @if($settings->email)
                            <li class="mb-2">
                                <i class="far fa-envelope me-2"></i> {{ $settings->email }}
                            </li>
                        @endif
                        @if($settings->address)
                            <li>
                                <i class="fas fa-map-marker-alt me-2"></i> {{ $settings->address }}
                            </li>
                        @endif
                    </ul>
                </div>

                {{-- Kebijakan + Sosmed --}}
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Kebijakan</h5>
                    <ul class="list-unstyled small mb-3">
                        <li><a href="{{ route('front.information.show', 'privacy-policy') }}" class="text-white text-decoration-none">Privacy Policy</a></li>
                        <li><a href="{{ route('front.information.show', 'terms-and-conditions') }}" class="text-white text-decoration-none">Syarat & Ketentuan</a></li>
                    </ul>

                    <h5 class="fw-bold mb-3">Ikuti Kami</h5>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3 fs-5">
                        @if($settings->facebook_url)
                            <a href="{{ $settings->facebook_url }}" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if($settings->instagram_url)
                            <a href="{{ $settings->instagram_url }}" class="text-white"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if($settings->tiktok_url)
                            <a href="{{ $settings->tiktok_url }}" class="text-white"><i class="fab fa-tiktok"></i></a>
                        @endif
                        @if($settings->youtube_url)
                            <a href="{{ $settings->youtube_url }}" class="text-white"><i class="fab fa-youtube"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Copyright --}}
            <div class="text-center pt-4 mt-4 small opacity-75">

                &copy; {{ date('Y') }} {{ $settings->company_name }}. All rights reserved.
            </div>
        </div>
    </footer>
@endif
