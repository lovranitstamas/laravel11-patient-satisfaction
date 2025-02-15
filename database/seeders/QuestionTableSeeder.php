<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Survey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionTableSeeder extends Seeder
{

  private const QUESTIONS = [
    [
      'question' => 'Mennyire elégedett az orvosi ellátás minőségével?',
      'survey_id' => null
    ],
    [
      'question' => 'Mennyire volt könnyű időpontot foglalni a vizsgálatra vagy kezelésre',
      'survey_id' => null
    ],
    [
      'question' => 'Mennyire volt barátságos és segítőkész az egészségügyi személyzet?',
      'survey_id' => null
    ],
    [
      'question' => 'Mennyire érezte úgy, hogy az orvos megfelelően tájékoztatta Önt az állapotáról és a kezelésről?',
      'survey_id' => null
    ],
    [
      'question' => 'Mennyire volt tiszta és higiénikus a kórház/rendelő környezete?',
      'survey_id' => null
    ],
    [
      'question' => 'Mennyi időt kellett várnia a vizsgálatra vagy kezelésre az érkezése után?',
      'survey_id' => null
    ],
  ];

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    $firstQuestionnaireId = Survey::first()?->id;

    foreach (self::QUESTIONS as $question) {
      Question::firstOrCreate([
        'survey_id' => $firstQuestionnaireId,
        'question' => $question['question']
      ]);
    }
  }
}
