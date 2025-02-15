<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\LoadingBay;
use App\Models\Question;
use App\Models\Survey;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class QuestionType extends GraphQLType
{
  protected $attributes = [
    'name' => 'QuestionType',
    'description' => 'A type',
    'model' => Question::class
  ];

  public function fields(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::int()),
        'description' => 'Id of a particular book',
      ],
      'survey' => [
        'type' => GraphQL::type('SurveyType'),
        'description' => 'Details of the survey',
        'resolve' => function ($root, $args) {
          return Survey::find($root['survey_id']);
        },
      ],
      'survey_id' => [
        'type' => Type::getNullableType(Type::int()),
        'description' => '',
      ],
      'question' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'The question is a string',
      ],
      'created_at' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'created_at',
      ],
    ];
  }
}
