@extends('layouts/layoutMaster')

@section('title', 'Create Account')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss'
  ])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js'
  ])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">

        {{-- Header / Breadcrumb --}}
        <div class="card-header">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom-icon mb-0">
              <li class="breadcrumb-item">
                <a href="{{ route('accounts.index') }}">Accounts</a>
                <i class="breadcrumb-icon ti tabler-chevron-right"></i>
              </li>
              <li class="breadcrumb-item active">Generate TOTP</li>
            </ol>
          </nav>
        </div>

        {{-- Body --}}
        <div class="card-body">
          <form method="POST" action="{{ route('angle-one.submit.step.three',$account->id) }}" class="row g-4">
            @csrf

            {{-- Client ID --}}
            <div class="col-12">
              <label class="form-label" for="client_id">
                Client ID <span class="text-danger">*</span>
              </label>

              <div class="input-group input-group-merge">
                <input
                  type="text"
                  id="client_id"
                  name="client_id"
                  class="form-control disabled"
                  disabled
                  value="{{ $account->client_id }}"
                  placeholder="e.g. AABBCC11"
                />
                <span class="input-group-text cursor-pointer">
                  <i
                    class="icon-base ti tabler-help-circle text-body-secondary"
                    data-bs-toggle="tooltip"
                    title="Client ID provided by AngelOne"
                  ></i>
                </span>
              </div>

              @error('client_id')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- PIN --}}
            <div class="col-12">
              <label class="form-label" for="pin">
                PIN <span class="text-danger">*</span>
              </label>

              <div class="input-group input-group-merge">
                <input
                  type="password"
                  id="pin"
                  name="pin"
                  maxlength="4"
                  class="form-control @error('pin') is-invalid @enderror"
                  placeholder="Enter 4-digit PIN"
                />
                <span class="input-group-text cursor-pointer">
                  <i
                    class="icon-base ti tabler-help-circle text-body-secondary"
                    data-bs-toggle="tooltip"
                    title="Enter your AngelOne account PIN"
                  ></i>
                </span>
              </div>

              @error('pin')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- Submit --}}
            <div class="col-12 text-end">
              <button type="submit" class="btn btn-primary">
                Submit
              </button>
            </div>

          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
