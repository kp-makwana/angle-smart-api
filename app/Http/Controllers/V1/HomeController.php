<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{

  public function dashboard()
  {
    $pageConfigs = ['myLayout' => 'horizontal'];

    return view('dashboard.dashboard',['pageConfigs'=> $pageConfigs]);
  }
  public function index()
  {
    $pageConfigs = ['myLayout' => 'front'];
    return view('frontend.index', ['pageConfigs' => $pageConfigs]);
  }
}
