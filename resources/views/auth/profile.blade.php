@extends('layouts/layoutMaster')
@section('title', 'My Profile')
@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
  ])
@endsection
@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
  ])
@endsection

@section('page-script')
  @vite(['resources/assets/js/pages-my-profile.js'])
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      @include('components.profile-breadcrumb')
      <div class="card">
        <div class="card-body">
          <form id="formAccountSettings"
                method="POST"
                enctype="multipart/form-data"
                action="{{ route('profile.update') }}">
            @csrf
            <div class="d-flex align-items-center gap-4 mb-6">
              <img
                src="{{ asset('assets/img/avatars/1.png') }}"
                class="rounded"
                height="100"
                width="100"
                alt="Profile"
                id="uploadedAvatar"
              />
              <div>
                <label class="btn btn-primary me-2 mb-2">
                  Upload Photo
                  <input
                    type="file"
                    name="profile_image"
                    class="d-none account-file-input"
                    accept="image/png, image/jpeg"
                  />
                </label>

                <button type="button" class="btn btn-label-secondary account-image-reset mb-2">
                  Reset
                </button>

                <div class="text-muted">
                  Allowed JPG, PNG. Max size 5MB
                </div>
              </div>
            </div>
            <div class="row g-4">
              <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" disabled class="form-control" value="{{ $user->email }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">Language</label>
                <select name="language" class="form-select">
                  <option value="en" {{ $user->language === 'en' ? 'selected' : '' }}>English</option>
                  <option value="hi" {{ $user->language === 'hi' ? 'selected' : '' }}>Hindi</option>
                </select>
              </div>
            </div>
            <div class="mt-6">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              <button type="reset" class="btn btn-label-secondary">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
