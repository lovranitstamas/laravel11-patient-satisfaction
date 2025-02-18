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
      'question' => 'Mennyire volt elégedett a várakozási idő hosszával az ellátás során?',
      'survey_id' => null
    ],
    [
      'question' => 'Mennyire volt elégedett az egészségügyi intézmény felszereltségével és eszközeivel?',
      'survey_id' => null
    ],
    [
      'question' => 'Milyen mértékben érezte úgy, hogy az egészségügyi személyzet figyelmes és tisztelettudó volt Önnel szemben?',
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
      'question' => 'Mennyire érezte magát biztonságban és megfelelően ellátva az egészségügyi intézményben?',
      'survey_id' => null
    ],
    [
      'question' => 'Mennyire volt gördülékeny a betegfelvételi folyamat?',
      'survey_id' => null
    ],
    [
      'question' => 'Mennyire volt elégedett az időpontfoglalás folyamatával és annak gördülékenységével?',
      'survey_id' => null
    ],
    [
      'question' => 'Mennyire volt elégedett az egészségügyi intézmény kommunikációjával?',
      'survey_id' => null
    ],
    [
      'question' => 'Milyen szintű bizalmat érzett az orvosával kapcsolatban?',
      'survey_id' => null
    ],
    [
      'question' => 'Mennyire volt gördülékeny az ellátás?',
      'survey_id' => null
    ],
    [
      'question' => 'Mennyire volt elégedett a kapott kezelés hatékonyságával?',
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
