<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
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
    return view('user_questionnaire.index');
  }
}
