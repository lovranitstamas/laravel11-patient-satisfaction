<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{

  public function __construct()
  {
    $this->middleware('guest');
  }

  /**
   * Get login
   *
   * @param Request $request
   * @return \Closure|\Illuminate\Container\Container|mixed|object|null
   */
  public function index(Request $request): mixed
  {
    return view('auth.login');
  }
}
