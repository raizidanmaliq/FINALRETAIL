@extends('layouts.auth.common.app')

@push('css')
<style>
    /*
      Aturan CSS ini menimpa font default AdminLTE.
      Poppins digunakan di seluruh halaman, termasuk form dan tombol.
      !important memastikan aturan ini diprioritaskan.
    */
    body {
        font-family: 'Poppins', sans-serif !important;
    }
</style>
@endpush

@section('content')
  <div class="card rounded">
    <div class="card-body rounded login-card-body">
      <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          @error('password')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div class="row justify-content-end">
          <div class="col-12">
            <button type="submit" class="btn btn-block" style="background:#A34A4A; color:white;">
    Sign In
</button>

          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
