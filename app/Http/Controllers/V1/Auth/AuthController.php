<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
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
    $pageConfigs = ['myLayout' => 'blank'];
    return view('auth.register', ['pageConfigs' => $pageConfigs]);
  }

  public function showLoginForm()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('auth.login',['pageConfigs' => $pageConfigs]);
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

  public function profile()
  {
    $user = auth()->user();
    $pageConfigs = ['myLayout' => 'horizontal'];
    return view('auth.profile',compact('pageConfigs','user'));
  }

  public function profileUpdate(Request $request)
  {
    return redirect()->back()->with('success', 'Profile updated successfully');
  }

  public function security()
  {
    $pageConfigs = ['myLayout' => 'horizontal'];
    return view('auth.security',compact('pageConfigs'));
  }

  public function securityUpdate(Request $request){
    dd($request->all());
    return redirect()->back()->with('success', 'Profile updated successfully');
  }
}
