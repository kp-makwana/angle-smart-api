@php
  $customizerHidden = 'customizer-hide';
  // $request object is available in the view thanks to the Controller's showResetForm method
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Reset Password')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('page-style')
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
  @vite(['resources/assets/js/pages-auth.js'])
@endsection

@section('content')
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-6">
        <div class="card">
          <div class="card-body">
            <div class="app-brand justify-content-center mb-6">
              <a href="{{ url('/') }}" class="app-brand-link">
                <span class="app-brand-logo demo">@include('_partials.macros')</span>
                <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
              </a>
            </div>
            <h4 class="mb-1">Reset Password 🔒</h4>
            <p class="mb-6"><span class="fw-medium">Enter your new password below</span></p>

            {{-- 🚨 Global Error Display (e.g., Token/Email failure) --}}
            @error('email')
            <div class="alert alert-danger p-2 mb-3 small" role="alert">
              {{ $message }}
            </div>
            @enderror
            @error('token')
            <div class="alert alert-danger p-2 mb-3 small" role="alert">
              {{ $message }}
            </div>
            @enderror

            <form id="reset-password" action="{{ route('password.update') }}" method="POST">
              @csrf

              {{-- 🔑 Hidden fields required for password reset --}}
              <input type="hidden" name="token" value="{{ $token }}">
              <input type="hidden" name="email" value="{{ $email }}">

              {{-- New Password Field --}}
              <div class="mb-6 form-password-toggle form-control-validation">
                <label class="form-label" for="password">New Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password"
                         placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                         aria-describedby="password" autofocus />
                  <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                </div>
                {{-- Password Validation Error --}}
                @error('password')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>

              {{-- Confirm Password Field --}}
              <div class="mb-6 form-password-toggle form-control-validation">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                         placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                         aria-describedby="password_confirmation" />
                  <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                </div>
                {{-- Confirmation errors are handled by the 'password' error above (due to 'confirmed' rule) --}}
              </div>

              <button class="btn btn-primary d-grid w-100 mb-6">Set new password</button>

              <div class="text-center">
                {{-- 🔙 Updated to use route('login') for reliability --}}
                <a href="{{ route('login') }}" class="d-flex justify-content-center">
                  <i class="icon-base ti tabler-chevron-left scaleX-n1-rtl me-1_5"></i>
                  Back to login
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
