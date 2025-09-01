@extends('layouts.customer.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Update Profile</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Update Profile</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <form action="{{ route('customer.profiles.update', auth()->guard('customer')->user()) }}" id="form-validation" method="POST">
        @csrf
        @method('PATCH')
        <div class="card-body">
            <div class="form-row">
                <section class="col-lg-6 form-group">
                    <label class="body-1 color-text" for="name">Full Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', auth()->guard('customer')->user()->name) }}" required>
                </section>

                <section class="col-lg-6 form-group">
                    <label class="body-1 color-text" for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email', auth()->guard('customer')->user()->email) }}" required>
                </section>

                <section class="col-lg-6 form-group">
                    <label class="body-1 color-text" for="phone">Phone Number</label>
                    <input type="tel" class="form-control" name="phone" id="phone" value="{{ old('phone', auth()->guard('customer')->user()->phone) }}" required>
                </section>

                <section class="col-lg-12 form-group">
                    <label class="body-1 color-text" for="address">Address</label>
                    <textarea class="form-control" name="address" id="address" rows="3" required>{{ old('address', auth()->guard('customer')->user()->address) }}</textarea>
                </section>

                <section class="col-lg-6 form-group">
                    <label class="body-1 color-text" for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </section>

                <section class="col-lg-6 form-group">
                    <label class="body-1 color-text" for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                </section>
            </div>
        </div>

        <div class="card-footer">
           <button class="btn text-white float-right" style="background-color: #9B4141;">Update Profile</button>

        </div>
    </form>
</section>
@endsection

@push('js')
<script>
    jQuery.extend(jQuery.validator.messages, {
        required: "Formulir ini wajib diisi.",
        email: "Isi dengan email yang valid.",
        url: "Isi dengan URL yang valid.",
        date: "Isi dengan tanggal yang valid",
        dateISO: "Please enter a valid date (ISO).",
        number: "Isi dengan angka yang valid",
        digits: "Hanya boleh memasukkan angka.",
        creditcard: "Harap masukkan nomor kartu kredit yang benar.",
        equalTo: "Harap masukkan kembali nilai yang sama.",
        accept: "Harap masukkan nilai dengan ekstensi yang valid.",
        maxlength: jQuery.validator.format("Harap masukkan tidak lebih dari {0} karakter."),
        minlength: jQuery.validator.format("Harap masukkan setidaknya {0} karakter."),
        rangelength: jQuery.validator.format("Harap masukkan nilai antara {0} dan {1} karakter."),
        range: jQuery.validator.format("Harap masukkan nilai antara {0} dan {1}."),
        max: jQuery.validator.format("Harap masukkan nilai kurang dari atau sama dengan {0}."),
        min: jQuery.validator.format("Harap masukkan nilai yang lebih besar dari atau sama dengan {0}.")
    });

    $(document).ready(function() {
        $('#form-validation').validate({
            rules: {
                phone: {
                    digits: true
                },
                password: {
                    minlength: 8
                },
                password_confirmation: {
                    equalTo: '#password'
                }
            },
            messages: {
                password_confirmation: {
                    equalTo: 'Password tidak sama'
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        })
    })
</script>
@endpush
