<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\PasswordService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
  protected PasswordService $service;

  public function __construct(PasswordService $service)
  {
    $this->service = $service;
  }

  public function showLinkRequestForm()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('auth.forgot-password', ['pageConfigs' => $pageConfigs]);
  }

  public function sendResetLinkEmail(Request $request)
  {
    $request->validate([
      'email' => 'required|email'
    ]);

    try {
      $this->service->sendResetLink($request->email);
    } catch (ValidationException $e) {
      return back()->withErrors(['email' => __('password.reset_link_failed')]);
    }

    return back()->with('success', __('password.reset_link_sent'));
  }

  public function showResetForm($token, Request $request)
  {
    $email = $request->input('email');
    $pageConfigs = ['myLayout' => 'blank'];
    return view('auth.reset-password', ['token' => $token,'pageConfigs' => $pageConfigs, 'email' => $email]);
  }

  public function resetPassword(Request $request)
  {
    $request->validate([
      'token'    => 'required',
      'email'    => 'required|email',
      'password' => 'required|min:6|confirmed',
    ]);

    try {
      $this->service->resetPassword([
        'email'                 => $request->email,
        'password'              => $request->password,
        'password_confirmation' => $request->password_confirmation,
        'token'                 => $request->token,
      ]);
    } catch (ValidationException $e) {
      return back()->withErrors(['email' => __('password.reset_failed')]);
    }

    return redirect()
      ->route('login')
      ->with('success', __('password.reset_success'));
  }
}
