<?php

namespace Database\Seeders;

use App\Models\Survey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveyTableSeeder extends Seeder
{

  private const QUESTIONNAIRES = [
    [
      'name' => 'Kérdéssor 1'
    ],
    [
      'name' => 'Kérdéssor 2',
    ],
  ];

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    foreach (self::QUESTIONNAIRES as $questionnaire) {
      Survey::firstOrCreate([
        'name' => $questionnaire['name']
      ]);
    }

  }
}
