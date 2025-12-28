@extends('layouts/layoutMaster')

@section('title', 'Account settings - Security')

{{-- Vendor Styles --}}
@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss'
  ])
@endsection

{{-- Vendor Scripts --}}
@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/cleave-zen/cleave-zen.js'
  ])
@endsection

@section('content')
  @include('components.profile-breadcrumb', ['title' => 'Security'])
  <div class="card mb-6">
    <h5 class="card-header">Change Password</h5>
    <div class="card-body pt-1">
      {{-- Success Message --}}
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <form id="formAccountSettings" method="POST" action="{{ route('security.update') }}">
        @csrf

        {{-- Current Password --}}
        <div class="row mb-sm-6 mb-2">
          <div class="col-md-6 form-password-toggle form-control-validation">
            <label class="form-label" for="current_password">Current Password</label>
            <div class="input-group input-group-merge">
              <input
                class="form-control @error('current_password') is-invalid @enderror"
                type="password"
                name="current_password"
                id="current_password"
                placeholder="••••••••••••"
              />
              <span class="input-group-text cursor-pointer">
                <i class="icon-base ti tabler-eye-off icon-xs"></i>
              </span>
              @error('current_password')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        {{-- New Password --}}
        <div class="row gy-sm-6 gy-2 mb-sm-0 mb-2">
          <div class="mb-6 col-md-6 form-password-toggle form-control-validation">
            <label class="form-label" for="password">New Password</label>
            <div class="input-group input-group-merge">
              <input
                class="form-control @error('password') is-invalid @enderror"
                type="password"
                name="password"
                id="password"
                placeholder="••••••••••••"
              />
              <span class="input-group-text cursor-pointer">
                <i class="icon-base ti tabler-eye-off icon-xs"></i>
              </span>
              @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Confirm Password --}}
          <div class="mb-6 col-md-6 form-password-toggle">
            <label class="form-label" for="confirmPassword">Confirm New Password</label>
            <div class="input-group input-group-merge">
              <input
                class="form-control"
                type="password"
                name="password_confirmation"
                id="confirmPassword"
                placeholder="••••••••••••"
              />
              <span class="input-group-text cursor-pointer">
                <i class="icon-base ti tabler-eye-off icon-xs"></i>
              </span>
            </div>
          </div>
        </div>

        {{-- Password Rules --}}
        <h6 class="text-body mt-4">Password Requirements:</h6>
        <ul class="ps-4 mb-0">
          <li class="mb-2">Minimum 8 characters long</li>
          <li class="mb-2">At least one lowercase character</li>
          <li>At least one number or symbol</li>
        </ul>

        {{-- Actions --}}
        <div class="mt-6">
          <button type="submit" class="btn btn-primary me-3">
            Save changes
          </button>
          <button type="reset" class="btn btn-label-secondary">
            Reset
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
