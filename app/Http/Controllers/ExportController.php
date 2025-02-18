<?php

namespace App\Http\Controllers;

use App\Exports\QuestionnaireExport;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Routing\ResponseFactory;

class ExportController extends Controller
{

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request, ResponseFactory $response): JsonResponse
  {

    if (Excel::store(new QuestionnaireExport($request->survey_id), "questionnaire_output/" .
      QuestionnaireExport::filename(), 'export')) {

      return $response->json([
        'message' => 'Exportálás megtörtént.'
      ], 200);
    }

    return $response->json([
      'message' => 'Exportálás sikertelen'
    ], 422);
  }

}
