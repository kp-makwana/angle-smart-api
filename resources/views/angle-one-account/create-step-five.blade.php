@extends('layouts/layoutMaster')

@section('title', 'Profile Almost Completed')

@section('content')
  <div class="row">
    <div class="col-12">

      {{-- ================= MAIN WRAPPER CARD ================= --}}
      <div class="card">

        {{-- ================= BREADCRUMB ================= --}}
        <div class="card-header">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom-icon mb-0">
              <li class="breadcrumb-item">
                <a href="{{ route('accounts.index') }}">Accounts</a>
                <i class="breadcrumb-icon ti tabler-chevron-right"></i>
              </li>
              <li class="breadcrumb-item active">
                Profile Almost Completed
              </li>
            </ol>
          </nav>
        </div>

        {{-- ================= CARD BODY ================= --}}
        <div class="card-body">

          <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">

              {{-- ================= INNER CONTENT CARD ================= --}}
              <div class="card border shadow-none text-center">
                <div class="card-header">
                  <h5 class="mb-1">Almost There! 🎉</h5>
                  <p class="text-muted mb-0">
                    Your profile is nearly complete
                  </p>
                </div>

                <div class="card-body">

                  <div class="mb-4">
                    <p class="fs-5 mb-2">
                      You’ve successfully verified your details.
                    </p>

                    <p class="text-muted">
                      To finish setting up your account, we need to create secure API access.
                      This will allow seamless and safe integration with our platform.
                    </p>
                  </div>

                  <div class="alert alert-info text-start mb-4">
                    <ul class="mb-0">
                      <li>🔐 Your data remains fully encrypted</li>
                      <li>⚡ API setup takes only a few seconds</li>
                      <li>✅ Required to complete your profile</li>
                    </ul>
                  </div>

                  <form method="POST"
                        action="{{ route('angle-one.submit.step.five', $account->id) }}"
                        id="finalStepForm">
                    @csrf

                    {{-- ================= ACTION BUTTONS ================= --}}
                    <div class="d-flex justify-content-end gap-2">
                      <button type="submit"
                              class="btn btn-primary d-inline-flex align-items-center px-6"
                              id="finalStepSubmitBtn">
                        <span class="btn-text">Create API & Continue</span>
                        <span class="spinner-border spinner-border-sm d-none ms-2"
                              role="status"
                              aria-hidden="true"></span>
                        <span class="btn-loading-text d-none ms-2">
                          Processing...
                        </span>
                      </button>
                      <a href="{{ route('accounts.index') }}"
                         class="btn btn-outline-secondary">
                        <i class="ti tabler-arrow-left me-1"></i> Back
                      </a>
                    </div>
                  </form>
                </div>
              </div>
              {{-- ================= END INNER CARD ================= --}}

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  {{-- ================= SUBMIT SPINNER SCRIPT ================= --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('finalStepForm');
      const btn = document.getElementById('finalStepSubmitBtn');

      if (!form || !btn) return;

      form.addEventListener('submit', () => {
        btn.disabled = true;
        btn.querySelector('.btn-text').classList.add('d-none');
        btn.querySelector('.spinner-border').classList.remove('d-none');
        btn.querySelector('.btn-loading-text').classList.remove('d-none');
      });
    });
  </script>
@endsection
