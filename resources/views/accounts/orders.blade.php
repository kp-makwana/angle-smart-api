@extends('layouts/layoutMaster')

@section('title', 'eCommerce Customer Details Overview - Apps')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/cleave-zen/cleave-zen.js',
  'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
  @vite([
    'resources/assets/js/modal-edit-user.js',
    'resources/assets/js/app-ecommerce-customer-detail.js',
    'resources/assets/js/app-ecommerce-customer-detail-overview.js'
  ])
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const deleteBtn = document.getElementById('btn-delete-account');
      if (!deleteBtn) return;

      deleteBtn.addEventListener('click', function () {
        const form = this.closest('form');

        Swal.fire({
          title: 'Are you sure?',
          text: `Are you sure to delete this account?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel',
          customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-label-secondary'
          },
          buttonsStyling: false
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  </script>
@endsection

@section('content')
  <div class="row">
    <!-- Customer-detail Sidebar -->
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
      <!-- Customer-detail Card -->
      <div class="card mb-6">
        <a href="{{ route('account.refresh',request('account')) }}"
           type="button"
           class="btn btn-lg btn-icon btn-outline-secondary position-absolute top-0 end-0 m-3"
           title="Refresh"
        >
          <i class="ti tabler-refresh"></i>
        </a>
        <div class="card-body pt-12">
          <div class="customer-avatar-section">
            <div class="d-flex align-items-center flex-column">
              <img class="img-fluid rounded mb-4" src="{{ asset('assets/img/avatars/1.png') }}" height="120" width="120"
                   alt="User avatar" />
              <div class="customer-info text-center mb-6">
                <h5 class="mb-0">{{ $account->nickname }}</h5>
                <span>#{{ $account->client_id }}</span>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-around flex-wrap mb-6 gap-0 gap-md-3 gap-lg-4">
            <div class="d-flex align-items-center gap-4 me-5">
              <div class="avatar">
                <div class="avatar-initial rounded bg-label-success">
                  <i class="icon-base ti tabler-currency-rupee icon-lg"></i>
                </div>
              </div>
              <div>
                <h5 class="mb-0">{{ $account->net ?? '0' }}</h5>
                <span>Net Amount</span>
              </div>
            </div>
            <div class="d-flex align-items-center gap-4">
              <div class="avatar">
                <div class="avatar-initial rounded bg-label-danger">
                  <i class="icon-base ti tabler-currency-rupee icon-lg"></i>
                </div>
              </div>
              <div>
                <h5 class="mb-0">{{ $account->amount_used ?? '0' }}</h5>
                <span>Used Amount</span>
              </div>
            </div>
          </div>

          <div class="info-container">
            <hr>
            {{--            <h5 class="pb-4 border-bottom text-capitalize mt-6 mb-4">Details</h5>--}}
            <ul class="list-unstyled mb-6">
              <li class="mb-2">
                <span class="h6 me-1">Name:</span>
                <span>{{ $account->account_name }}</span>
              </li>
            </ul>
            <div class="d-flex justify-content-center gap-2">
              <a href="javascript:;" class="btn btn-primary w-50" data-bs-target="#editUser" data-bs-toggle="modal">Edit
                Details</a>
              <form action="{{ route('accounts.destroy', request('account')) }}"
                    method="POST"
                    class="w-50 delete-account-form">
                @csrf
                @method('DELETE')

                <button type="button" id="btn-delete-account"
                        class="btn btn-danger w-100 btn-delete-account"
                        data-name="{{ $account->nickname ?: $account->client_id }}">
                  Delete Customer
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- /Customer-detail Card -->
      <!-- Plan Card -->

    </div>
    <!--/ Customer Sidebar -->

    <!-- Customer Content -->
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
      <!-- Customer Pills -->
      @include('components.account-breadcrumb')
      <!--/ Customer Pills -->

      <!-- / Customer cards -->
      <!-- Invoice table -->
      <div class="col-md-12 col-xxl-12">
        <div class="card h-100">

          <!-- Header -->
          <div class="card-header d-flex align-items-center justify-content-between">
            <div>
              <h5 class="mb-1">Today’s Orders Book</h5>
{{--              <p class="text-muted mb-0">Equity orders (static demo)</p>--}}
            </div>

            <button class="btn btn-sm btn-outline-primary" onclick="window.location.reload()">
              <i class="icon-base ti tabler-refresh me-1"></i> Refresh
            </button>
          </div>

          <!-- Tabs -->
          <div class="card-body p-0">
            <ul class="nav nav-tabs nav-fill border-bottom" role="tablist">
              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-new">
                  New
                </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-preparing">
                  Preparing
                </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-shipping">
                  Shipping
                </button>
              </li>
            </ul>

            <div class="tab-content" style="padding: 0 !important;">

              <!-- ================= NEW ORDERS ================= -->
              <div class="card tab-pane fade show active" id="tab-new">
                <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                      <th>Symbol</th>
                      <th>Qty</th>
                      <th>Order Price</th>
                      <th>LTP</th>
                      <th>Status</th>
                      <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                      <td>
                        <span class="fw-semibold">RELIANCE</span>
                        <div class="small text-muted">NSE · CNC</div>
                      </td>
                      <td>
                        <span class="fw-semibold">10</span>
                      </td>
                      <td>₹2,456.50</td>
                      <td>
                        <span class="text-success">₹2,462.10</span>
                      </td>
                      <td>
                        <span class="badge bg-label-warning">Pending</span>
                      </td>
                      <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                          <button class="btn btn-sm btn-success">Buy</button>
                          <button class="btn btn-sm btn-danger">Sell</button>
                        </div>
                      </td>
                    </tr>

                    </tbody>
                  </table>
                </div>
              </div>

              <!-- ================= PREPARING ================= -->
              <div class="tab-pane fade" id="tab-preparing">
                <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                      <th>Symbol</th>
                      <th>Qty</th>
                      <th>Avg Price</th>
                      <th>LTP</th>
                      <th>Status</th>
                      <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                      <td>
                        <span class="fw-semibold">TCS</span>
                        <div class="small text-muted">NSE · MIS</div>
                      </td>
                      <td>
                        <span class="fw-semibold">5</span>
                      </td>
                      <td>₹3,821.00</td>
                      <td>
                        <span class="text-success">₹3,835.40</span>
                      </td>
                      <td>
                        <span class="badge bg-label-info">Executing</span>
                      </td>
                      <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                          <button class="btn btn-sm btn-outline-secondary" disabled>
                            In Progress
                          </button>
                        </div>
                      </td>
                    </tr>

                    </tbody>
                  </table>
                </div>
              </div>

              <!-- ================= SHIPPING ================= -->
              <div class="tab-pane fade" id="tab-shipping">
                <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                      <th>Symbol</th>
                      <th>Qty</th>
                      <th>Avg Price</th>
                      <th>P&amp;L</th>
                      <th>Status</th>
                      <th class="text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                      <td>
                        <span class="fw-semibold">INFY</span>
                        <div class="small text-muted">NSE · CNC</div>
                      </td>
                      <td>
                        <span class="fw-semibold">20</span>
                      </td>
                      <td>₹1,482.30</td>
                      <td>
                        <span class="text-success fw-semibold">₹+312.40</span>
                        <div class="small text-success">+1.05%</div>
                      </td>
                      <td>
                        <span class="badge bg-label-success">Completed</span>
                      </td>
                      <td class="text-end">
                        <button class="btn btn-sm btn-outline-primary">
                          View
                        </button>
                      </td>
                    </tr>

                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>


      <!-- /Invoice table -->
    </div>
    <!--/ Customer Content -->
  </div>

  <!-- Modal -->
  @include('_partials/_modals/modal-edit-user')
  @include('_partials/_modals/modal-upgrade-plan')
  <!-- /Modal -->
@endsection

