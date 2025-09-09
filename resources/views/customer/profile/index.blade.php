@extends('layouts.common.app')

@section('header')
<header class="container py-3">
    <div class="row align-items-center">
        <div class="col-sm-6 d-flex align-items-center">
            <i class="la la-user-edit fs-2 text-danger me-2"></i>
            <h2 class="fw-bold text-dark mb-0">Update Profile</h2>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" class="text-decoration-none">
                            <i class="la la-home text-secondary me-1"></i> Home
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Update Profile
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="container my-4" style="max-width: 1140px;">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <!-- Header Card -->
                <div class="card-header text-white rounded-top-4 py-3 border-0"
                     style="background: linear-gradient(90deg,#9B4141,#C25B5B) !important;">
                    <h5 class="mb-0 fw-semibold">
                        <i class="la la-id-card me-2"></i> Edit Your Information
                    </h5>
                </div>

                <!-- Form -->
                <form action="{{ route('customer.profiles.update', auth()->guard('customer')->user()) }}"
                      id="form-validation" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Full Name -->
                            <div class="col-md-6 form-group">
                                <label for="name" class="form-label fw-medium">
                                    <i class="la la-user text-danger me-1"></i> Full Name
                                </label>
                                <input type="text" class="form-control shadow-sm" name="name" id="name"
                                       value="{{ old('name', auth()->guard('customer')->user()->name) }}" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 form-group">
                                <label for="email" class="form-label fw-medium">
                                    <i class="la la-envelope text-danger me-1"></i> Email
                                </label>
                                <input type="email" class="form-control shadow-sm" name="email" id="email"
                                       value="{{ old('email', auth()->guard('customer')->user()->email) }}" required>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 form-group">
                                <label for="phone" class="form-label fw-medium">
                                    <i class="la la-phone text-danger me-1"></i> Phone Number
                                </label>
                                <input type="tel" class="form-control shadow-sm" name="phone" id="phone"
                                       value="{{ old('phone', auth()->guard('customer')->user()->phone) }}" required>
                            </div>

                            <!-- Address -->
                            <div class="col-12 form-group">
                                <label for="address" class="form-label fw-medium">
                                    <i class="la la-map-marker text-danger me-1"></i> Address
                                </label>
                                <textarea class="form-control shadow-sm" name="address" id="address" rows="3" required>{{ old('address', auth()->guard('customer')->user()->address) }}</textarea>
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 form-group position-relative">
                                <label for="password" class="form-label fw-medium">
                                    <i class="la la-lock text-danger me-1"></i> Password
                                </label>
                                <input type="password" class="form-control shadow-sm" name="password" id="password">
                                <i class="la la-eye position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                                   style="cursor:pointer;" onclick="togglePassword('password', this)"></i>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 form-group position-relative">
                                <label for="password_confirmation" class="form-label fw-medium">
                                    <i class="la la-lock text-danger me-1"></i> Confirm Password
                                </label>
                                <input type="password" class="form-control shadow-sm" name="password_confirmation" id="password_confirmation">
                                <i class="la la-eye position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                                   style="cursor:pointer;" onclick="togglePassword('password_confirmation', this)"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-0 text-end rounded-bottom-4">
                        <button class="btn px-4 py-2 text-white fw-semibold shadow-sm"
                                style="background: linear-gradient(90deg,#9B4141,#C25B5B); border-radius: 8px;">
                            <i class="la la-save me-1"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId, el) {
    const input = document.getElementById(fieldId);
    if (input.type === "password") {
        input.type = "text";
        el.classList.replace("la-eye", "la-eye-slash");
    } else {
        input.type = "password";
        el.classList.replace("la-eye-slash", "la-eye");
    }
}
</script>
@endsection

@push('js')
<script>
    jQuery.extend(jQuery.validator.messages, {
        required: "Formulir ini wajib diisi.",
        email: "Isi dengan email yang valid.",
        digits: "Hanya boleh memasukkan angka.",
        equalTo: "Harap masukkan kembali nilai yang sama.",
        minlength: jQuery.validator.format("Harap masukkan setidaknya {0} karakter.")
    });

    $(document).ready(function() {
        $('#form-validation').validate({
            rules: {
                phone: { digits: true },
                password: { minlength: 8 },
                password_confirmation: { equalTo: '#password' }
            },
            messages: {
                password_confirmation: { equalTo: 'Password tidak sama' }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endpush
