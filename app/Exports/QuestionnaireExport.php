<?php

namespace App\Exports;

use App\Models\Survey;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Excel;

class QuestionnaireExport implements FromCollection, Responsable, WithHeadings
{

  use Exportable;

  protected int $surveyId;

  /**
   * Optional Writer Type
   */
  private string $writerType = Excel::XLSX;

  function __construct($surveyId)
  {
    $this->surveyId = (int)$surveyId;
  }

  public static function filename(): string
  {
    return Carbon::now()->format('Y-m-d_H-i') . '_questionnaire_export.xlsx';
  }

  public function headings(): array
  {
    return [
      'ID',
      'Beküldő neve',
      'Nem',
      'Email cím',
      'Kérdőív neve',
      'Kérdés',
      'Válasz'
    ];
  }

  /**
   * @return Collection
   */
  public function collection(): Collection
  {
    return Survey::where('id', $this->surveyId)
      ->with(['questions.survey_responses'])
      ->get()
      ->flatMap(function ($survey) {
        return $survey->questions->flatMap(function ($question) use($survey) {
          return $question->survey_responses->map(function ($response) use ($question, $survey) {
            return [
              'id' => $response->id,
              'submitter_name' => $response->submitter_name ?? 'N/A',
              'gender' => $response->gender ?? 'N/A',
              'email' => $response->email ?? 'N/A',
              'survey_name' => $survey->name,
              'question' => $question->question,
              'answer' => $response->response,
            ];
          });
        });
      });
  }
}
