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

      <form id="formAccountSettings" method="POST" action="{{ route('security.update') }}">
        @csrf

        {{-- Current Password --}}
        <div class="row mb-4">
          <div class="col-md-6 form-password-toggle form-control-validation">
            <label class="form-label" for="current_password">Current Password</label>

            <div class="input-group input-group-merge @error('current_password') is-invalid @enderror">
              <input
                type="password"
                name="current_password"
                id="current_password"
                class="form-control @error('current_password') is-invalid @enderror"
                placeholder="••••••••••••"
              />
              <span class="input-group-text cursor-pointer">
          <i class="icon-base ti tabler-eye-off icon-xs"></i>
        </span>
            </div>

            @error('current_password')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>

        {{-- New Password --}}
        <div class="row mb-4">
          <div class="col-md-6 form-password-toggle form-control-validation">
            <label class="form-label" for="password">New Password</label>

            <div class="input-group input-group-merge @error('password') is-invalid @enderror">
              <input
                type="password"
                name="password"
                id="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="••••••••••••"
              />
              <span class="input-group-text cursor-pointer">
          <i class="icon-base ti tabler-eye-off icon-xs"></i>
        </span>
            </div>

            @error('password')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
            @enderror
          </div>

          {{-- Confirm Password --}}
          <div class="col-md-6 form-password-toggle form-control-validation">
            <label class="form-label" for="password_confirmation">Confirm New Password</label>

            <div class="input-group input-group-merge @error('password_confirmation') is-invalid @enderror">
              <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="••••••••••••"
              />
              <span class="input-group-text cursor-pointer">
          <i class="icon-base ti tabler-eye-off icon-xs"></i>
        </span>
            </div>

            @error('password_confirmation')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>

        {{-- Password Rules --}}
        <h6 class="text-body mt-3">Password Requirements:</h6>
        <ul class="ps-4 mb-4 text-muted">
          <li>Minimum 8 characters long</li>
          <li>At least one lowercase letter</li>
          <li>At least one number or symbol</li>
        </ul>

        {{-- Actions --}}
        <div class="mt-4">
          <button type="submit" class="btn btn-primary me-3" id="profileSubmitBtn">
            <span class="btn-text">Save Changes</span>
            <span class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
            <span class="btn-loading-text d-none ms-2">Updating...</span>
          </button>
          <button type="reset" class="btn btn-label-secondary">
            Reset
          </button>
        </div>
      </form>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const profileForm = document.getElementById('formAccountSettings');
      const submitBtn = document.getElementById('profileSubmitBtn');

      if (profileForm && submitBtn) {
        profileForm.addEventListener('submit', function() {
          // Disable button to prevent double submission
          submitBtn.disabled = true;

          // Toggle visibility of text and spinner
          submitBtn.querySelector('.btn-text').classList.add('d-none');
          submitBtn.querySelector('.spinner-border').classList.remove('d-none');
          submitBtn.querySelector('.btn-loading-text').classList.remove('d-none');
        });
      }
    });
  </script>
@endsection
