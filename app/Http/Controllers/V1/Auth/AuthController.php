<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  protected AuthService $auth;

  public function __construct(AuthService $auth)
  {
    $this->auth = $auth;
  }

  public function showRegisterForm()
  {
    return view('auth.register');
  }

  public function showLoginForm()
  {
    return view('auth.login');
  }

  /**
   * Register user + redirect to OTP page
   */
  public function register(RegisterRequest $request)
  {
    $user = $this->auth->register($request->validated());

    return redirect()
      ->route('verify.otp', $user->id)
      ->with('success', __('auth.register_success'));
  }

  /**
   * Login logic (only after verification)
   */
  public function login(LoginRequest $request)
  {
    try {
      $this->auth->login($request->validated());
    } catch (ValidationException $e) {
      return back()
        ->withErrors(['email' => __('auth.email_verify_first')])
        ->withInput();
    }

    return redirect()
      ->route('dashboard')
      ->with('success', __('auth.login_success'));
  }

  /**
   * Logout
   */
  public function logout()
  {
    auth()->logout();

    return redirect()
      ->route('login')
      ->with('success', __('auth.logout_success'));
  }
}
