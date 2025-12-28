<div class="nav-align-top">
  <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
    <li class="nav-item">
      <a class="nav-link {{ \Illuminate\Support\Facades\Route::is('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
        <i class="icon-base ti tabler-users icon-sm me-1_5"></i> Account
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ \Illuminate\Support\Facades\Route::is('security') ? 'active' : '' }}" href="{{ route('security') }}">
        <i class="icon-base ti tabler-lock icon-sm me-1_5"></i> Security
      </a>
    </li>
  </ul>
</div>
