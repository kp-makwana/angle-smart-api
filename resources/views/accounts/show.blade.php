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
  <script>
    /* =====================================================
       CONFIG FROM BACKEND
    ===================================================== */
    const clientCode = @json($account->client_id);
    const feedToken  = @json($account->feed_token);
    const apiKey     = @json($account->api_key);
    const tokens     = @json($tokens);

    /* =====================================================
       GLOBAL STATE
    ===================================================== */
    const ltpCellMap = {};     // token => td[]
    const ltpCache   = {};     // token => ltp
    let activeToken  = null;   // currently opened modal token

    /* =====================================================
       DOM READY
    ===================================================== */
    document.addEventListener('DOMContentLoaded', () => {

      /* -----------------------------
         MAP TABLE CELLS BY TOKEN
      ----------------------------- */
      document.querySelectorAll('.ltp-cell').forEach(td => {
        const token = td.dataset.token;
        if (!token) return;

        if (!ltpCellMap[token]) ltpCellMap[token] = [];
        ltpCellMap[token].push(td);
      });

      /* -----------------------------
         SOCKET CONNECTION
      ----------------------------- */
      const socket = new WebSocket(
        `wss://smartapisocket.angelone.in/smart-stream` +
        `?clientCode=${encodeURIComponent(clientCode)}` +
        `&feedToken=${encodeURIComponent(feedToken)}` +
        `&apiKey=${encodeURIComponent(apiKey)}`
      );

      socket.binaryType = 'arraybuffer';

      socket.onopen = () => {
        socket.send(JSON.stringify({
          correlationID: clientCode,
          action: 1,
          params: {
            mode: 2, // QUOTE
            tokenList: [{
              exchangeType: 1, // NSE_CM
              tokens: tokens
            }]
          }
        }));
      };

      socket.onmessage = (event) => {
        if (!(event.data instanceof ArrayBuffer)) return;

        const view  = new DataView(event.data);
        const bytes = new Uint8Array(event.data);

        // QUOTE MODE ONLY
        if (view.getInt8(0) !== 2) return;

        /* -----------------------------
           TOKEN (BYTE 2–26)
        ----------------------------- */
        let token = '';
        for (let i = 2; i < 27; i++) {
          if (bytes[i] === 0) break;
          token += String.fromCharCode(bytes[i]);
        }

        /* -----------------------------
           PRICES
        ----------------------------- */
        const ltp   = view.getInt32(43, true) / 100;
        const close = Number(view.getBigInt64(115, true)) / 100;

        if (!ltp || !close) return;

        ltpCache[token] = ltp;

        const diff = ltp - close;
        const pct  = (diff / close) * 100;
        const cls  = diff >= 0 ? 'text-success' : 'text-danger';
        const sign = diff >= 0 ? '+' : '';

        /* -----------------------------
           UPDATE TABLE CELLS
        ----------------------------- */
        (ltpCellMap[token] || []).forEach(td => {

          const orderType  = td.dataset.orderType;
          const orderPrice = parseFloat(td.dataset.orderPrice || 0);

          td.querySelector('.order-price').innerHTML =
            orderType === 'market'
              ? 'AT MARKET'
              : `₹${orderPrice.toFixed(2)}`;

          td.querySelector('.ltp-live').innerHTML = `
        LTP ₹${ltp.toFixed(2)}
        <span class="${cls}">
          (${sign}${pct.toFixed(2)}%)
        </span>
      `;
        });

        /* -----------------------------
           UPDATE ACTIVE MODAL ONLY
        ----------------------------- */
        if (token === activeToken) {
          const ltpSpan = document.getElementById('om-ltp');
          if (ltpSpan) {
            ltpSpan.innerText = ltp.toFixed(2);
          }
        }
      };

      socket.onerror = err => console.error('❌ Socket Error', err);
      socket.onclose = () => console.warn('⚠️ Socket Closed');
    });

    /* =====================================================
       OPEN COMMON ORDER MODAL
    ===================================================== */
    function openOrderModal(order, side = 'BUY') {

      activeToken = order.symboltoken;

      // Hidden fields
      document.getElementById('om-symbol-token').value  = order.symboltoken;
      document.getElementById('om-tradingsymbol').value = order.tradingsymbol;
      document.getElementById('om-transactiontype').value = side;
      document.getElementById('om-variety').value = order.variety || 'NORMAL';

      // Visible fields
      document.getElementById('om-symbol').value = order.tradingsymbol;
      document.getElementById('om-side').value   = side;
      if(side !== 'BUY'){
        document.getElementById('om-qty').value    = order.quantity || 1;
      } else {
        document.getElementById('om-qty').value    = 0;
      }

      // Order type
      const orderTypeSelect = document.getElementById('om-ordertype');
      orderTypeSelect.value = order.ordertype || 'MARKET';

      const priceInput = document.getElementById('om-price');

      if (orderTypeSelect.value === 'MARKET') {
        priceInput.value = '';
        priceInput.disabled = true;
        priceInput.placeholder = 'AT MARKET';
      } else {
        priceInput.disabled = false;
        priceInput.value = order.price || '';
      }

      // LTP
      document.getElementById('om-ltp').innerText =
        ltpCache[order.symboltoken]
          ? ltpCache[order.symboltoken].toFixed(2)
          : '—';

      // UI style
      document.getElementById('om-title').innerText =
        side === 'SELL' ? 'Sell Order' : 'Buy / Modify Order';

      const submitBtn = document.getElementById('om-submit-btn');
      submitBtn.className = side === 'SELL'
        ? 'btn btn-danger'
        : 'btn btn-success';

      new bootstrap.Modal(document.getElementById('orderModal')).show();
    }

    /* =====================================================
       ORDER TYPE CHANGE (MARKET / LIMIT)
    ===================================================== */
    document.addEventListener('change', (e) => {
      if (e.target.id !== 'om-ordertype') return;

      const priceInput = document.getElementById('om-price');

      if (e.target.value === 'MARKET') {
        priceInput.value = '';
        priceInput.disabled = true;
        priceInput.placeholder = 'AT MARKET';
        return;
      }

      priceInput.disabled = false;
      priceInput.placeholder = 'Enter limit price';

      // Angel One behavior → autofill LTP
      if (activeToken && ltpCache[activeToken]) {
        priceInput.value = ltpCache[activeToken].toFixed(2);
      }
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
      <div class="card">
        <div class="card-header border-bottom">
          <div class="row align-items-center g-3">

            <!-- Holding Value -->
            <div class="col-md-2 col-6">
              <div class="d-flex align-items-center gap-2">
                <div class="avatar avatar-sm">
                  <div class="avatar-initial rounded bg-label-primary">
                    <i class="icon-base ti tabler-wallet"></i>
                  </div>
                </div>
                <div>
                  <small class="text-muted d-block">Current Value</small>
                  <span class="fw-semibold">&#8377;{{ $summary['totalholdingvalue'] ?? 0 }}</span>
                </div>
              </div>
            </div>

            <!-- Investment -->
            <div class="col-md-2 col-6">
              <div class="d-flex align-items-center gap-2">
                <div class="avatar avatar-sm">
                  <div class="avatar-initial rounded bg-label-info">
                    <i class="icon-base ti tabler-currency-rupee"></i>
                  </div>
                </div>
                <div>
                  <small class="text-muted d-block">Investment</small>
                  <span class="fw-semibold">&#8377;{{ $summary['totalinvvalue'] ?? 0 }}</span>
                </div>
              </div>
            </div>

            <!-- P&L -->
            <div class="col-md-2 col-6">
              <div class="d-flex align-items-center gap-2">
                <div class="avatar avatar-sm">
                  <div class="avatar-initial rounded
            {{ ($summary['totalprofitandloss'] ?? 0) >= 0 ? 'bg-label-success' : 'bg-label-danger' }}">
                    <i class="icon-base ti tabler-trending-up"></i>
                  </div>
                </div>
                <div>
                  <small class="text-muted d-block">P&amp;L</small>
                  <span class="fw-semibold
            {{ ($summary['totalprofitandloss'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
            &#8377;{{ $summary['totalprofitandloss'] ?? 0 }}
          </span>
                </div>
              </div>
            </div>

            <!-- P&L % -->
            <div class="col-md-2 col-6">
              <div class="d-flex align-items-center gap-2">
                <div class="avatar avatar-sm">
                  <div class="avatar-initial rounded
            {{ ($summary['totalpnlpercentage'] ?? 0) >= 0 ? 'bg-label-success' : 'bg-label-danger' }}">
                    <i class="icon-base ti tabler-percentage"></i>
                  </div>
                </div>
                <div>
                  <small class="text-muted d-block">P&amp;L %</small>
                  <span class="fw-semibold
            {{ ($summary['totalpnlpercentage'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
            {{ $summary['totalpnlpercentage'] ?? 0 }}%
          </span>
                </div>
              </div>
            </div>

            <!-- Refresh -->
            <div class="col-md-4 text-md-end text-start">
              <button type="button"
                      class="btn btn-sm btn-outline-primary"
                      onclick="window.location.reload()">
                <i class="icon-base ti tabler-refresh me-1"></i> Refresh
              </button>
            </div>

          </div>
        </div>
        <div class="table-responsive text-nowrap">
          <table class="table table-hover align-middle">
            <thead>
            <tr>
              <th>Symbol</th>
              <th>Quantity</th>
              <th>Avg Price</th>
              <th>LTP</th>
              <th>P&amp;L</th>
              <th class="text-end">Actions</th>
            </tr>
            </thead>

            <tbody class="table-border-bottom-0">
            @forelse($holdings as $row)
              <tr>

                <!-- Symbol -->
                <td>
                  <span class="fw-semibold">{{ $row['tradingsymbol'] }}</span>
                  <div class="text-muted small">{{ $row['exchange'] ?? 'NSE' }}</div>
                </td>

                <!-- Quantity -->
                <td>
                  <span class="fw-semibold">{{ $row['quantity'] }}</span>
                  <div class="text-muted small">
                    T1: {{ $row['t1quantity'] ?? 0 }}
                  </div>
                </td>

                <!-- Avg Price -->
                <td>
                  ₹{{ number_format($row['averageprice'], 2) }}
                </td>

                <!-- LTP -->
                <td>
                  <span class="{{ $row['ltp'] > $row['averageprice'] ? 'text-success' : 'text-danger' }}">
                    ₹{{ number_format($row['ltp'], 2) }}
                  </span>
                </td>

                <!-- P&L -->
                <td>
          <span class="fw-semibold
            {{ $row['profitandloss'] >= 0 ? 'text-success' : 'text-danger' }}">
            ₹{{ number_format($row['profitandloss'], 2) }}
          </span>
                  <div class="small
            {{ $row['pnlpercentage'] >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $row['pnlpercentage'] }}%
                  </div>
                </td>

                <!-- Actions -->
                <td class="text-end">
                  <div class="d-flex justify-content-end gap-2">

                    <!-- BUY / MODIFY -->
                    <a href="javascript:void(0)"
                       class="btn btn-sm btn-outline-success"
                       data-order='@json($row)'
                       onclick="openOrderModal(JSON.parse(this.dataset.order), 'BUY')">
                      Buy
                    </a>

                    <!-- SELL -->
                    <a href="javascript:void(0)"
                       class="btn btn-sm btn-outline-danger"
                       data-order='@json($row)'
                       onclick="openOrderModal(JSON.parse(this.dataset.order), 'SELL')">
                      Sell
                    </a>

                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  No holdings found
                </td>
              </tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <!-- /Invoice table -->
    </div>
    <!--/ Customer Content -->
  </div>

  <!-- Modal -->
  @include('_partials/_modals/modal-edit-user')
  <div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-simple">
      <div class="modal-content">
        <div class="modal-body">

          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

          <h4 class="text-center mb-3" id="om-title">Place Order</h4>

          <form id="orderForm"
                method="POST"
                action="{{ route('account.place.order', request('account')->id) }}"
                class="row g-4">

            @csrf

            <!-- REQUIRED HIDDEN FIELDS -->
            <input type="hidden" name="symboltoken" id="om-symbol-token">
            <input type="hidden" name="tradingsymbol" id="om-tradingsymbol">
            <input type="hidden" name="transactiontype" id="om-transactiontype">
            <input type="hidden" name="variety" id="om-variety" value="NORMAL">
            <input type="hidden" name="exchange" value="NSE">
            <input type="hidden" name="producttype" value="DELIVERY">
            <input type="hidden" name="duration" value="DAY">

            <!-- SYMBOL -->
            <div class="col-md-6">
              <label class="form-label">Symbol</label>
              <input type="text" class="form-control" id="om-symbol" readonly>
            </div>

            <!-- SIDE -->
            <div class="col-md-6">
              <label class="form-label">Side</label>
              <input type="text" class="form-control" id="om-side" readonly>
            </div>

            <!-- ORDER TYPE -->
            <div class="col-md-6">
              <label class="form-label">Order Type</label>
              <select class="form-select" name="ordertype" id="om-ordertype">
                <option value="MARKET">MARKET</option>
                <option value="LIMIT">LIMIT</option>
              </select>
            </div>

            <!-- QUANTITY -->
            <div class="col-md-6">
              <label class="form-label">Quantity</label>
              <input type="number" name="quantity" class="form-control" id="om-qty" required>
            </div>

            <!-- PRICE -->
            <div class="col-md-6">
              <label class="form-label">Price</label>
              <input type="number" step="0.05" name="price" class="form-control" id="om-price">
            </div>

            <!-- LIVE LTP -->
            <div class="col-md-6">
              <label class="form-label">Live Price</label>
              <div class="form-control bg-light">
                LTP ₹<span id="om-ltp">—</span>
              </div>
            </div>

            <div class="col-12 text-center mt-3">
              <button type="submit" class="btn btn-primary" id="om-submit-btn">
                Submit Order
              </button>
              <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                Cancel
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

  <!-- /Modal -->
@endsection

