<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\V1\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'horizontal'];
    return view('accounts.index',compact('pageConfigs'));
  }

  public function list(Request $request)
  {
    $query = Account::query();

    if (!auth()->user()->hasRole('admin')) {
      $query->where('user_id', auth()->id());
    }

    // SEARCH
    $search = $request->input('search.value');
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('account_name', 'like', "%{$search}%")
          ->orWhere('client_id', 'like', "%{$search}%")
          ->orWhere('status', 'like', "%{$search}%");
      });
    }

    $recordsTotal = $query->count();

    // Pagination
    $start = $request->input('start', 0);
    $length = $request->input('length', 10);

    $accounts = $query->skip($start)->take($length)->get();

    return response()->json([
      "draw" => intval($request->input('draw')),
      "recordsTotal" => $recordsTotal,
      "recordsFiltered" => $recordsTotal,

      "data" => $accounts->map(function ($acc) {
        return [
          "id"            => $acc->id,
          "client_display"=> $acc->client_id . " (" . ($acc->account_name ?? "N/A") . ")",
          "client_id"     => $acc->client_id,
          "account_name"  => $acc->account_name,
          "status"        => $acc->status,
          "is_active"     => $acc->is_active ? 1 : 0,
          "status_label"  => ucfirst($acc->status),
          "token_expiry"  => $acc->token_expiry,
          "last_login_at" => $acc->last_login_at,
        ];
      }),
    ]);
  }
}
