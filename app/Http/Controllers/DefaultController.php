<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DefaultController extends Controller
{

  /**
   * Handle the incoming request.
   *
   * @param \Illuminate\Http\Request $request
   * @return Application|Factory|View
   */
  public function __invoke(Request $request): Factory|View|Application
  {

    $viewPath = $this->getFolderPath($request->path());

    return view($viewPath);
  }

  /**
   * Get the folder path of the index blade file from the path
   *
   * @param string $path
   *
   * @return null|string $viewPath
   */
  private function getFolderPath(string $path): ?string
  {
    $chunks = explode("/", $path);
    // array:1 [
    //  0 => "exchanges"
    //]

    $pathParts = (count($chunks) > 1) ? explode("-", $chunks[0]) : explode("-", $path);
    // array:1 [
    //  0 => "exchanges"
    //]

    //"admin-test/user-management" =>: admin.test.user-management

    // Make the first part of the path to plural
    // Eg.: It makes from user path to users
    $viewPath = Str::plural($pathParts[0]);
    // exchanges => exchanges
    // admin => admin

    if (count($pathParts) > 0) {
      // Start from 1 because we have the first part of the folder
      for ($i = 1; $i < count($pathParts); $i++) {
        $viewPath .= ".{$pathParts[$i]}";
      }
      // $viewPath = admin.test.user-management
    }

    $view = explode('/', $path);

    $viewPath .= (count($view) > 1) ? "." . $view[count($view) - 1] . ".index" : ".index";

    return $viewPath;
  }
}
