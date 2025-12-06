<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\V1\Account;

class AccountController extends Controller
{
  public function index()
  {
    $this->authorize('viewAny', Account::class);
    $pageConfigs = ['myLayout' => 'horizontal'];
    return view('content.apps.app-user-list',compact('pageConfigs'));
  }
}
