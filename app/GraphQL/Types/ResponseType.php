<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Question;
use App\Models\Response;
use App\Models\Survey;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ResponseType extends GraphQLType
{
  protected $attributes = [
    'name' => 'ResponseType',
    'description' => 'A type',
    'model' => Response::class
  ];

  public function fields(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::int()),
        'description' => 'Id of a particular response',
      ],
      'submitter_name' => [
        'type' => Type::getNullableType(Type::string()),
        'description' => 'The submitter name is a string',
      ],
      'email' => [
        'type' => Type::getNullableType(Type::string()),
        'description' => 'The email is a string',
      ],
      'survey_id' => [
        'type' => Type::getNullableType(Type::int()),
        'description' => '',
      ],
      'survey' => [
        'type' => GraphQL::type('SurveyType'),
        'description' => 'Details of the survey',
        'resolve' => function ($root, $args) {
          return Survey::find($root['survey_id']);
        },
      ],
      'question_id' => [
        'type' => Type::getNullableType(Type::int()),
        'description' => '',
      ],
      'question' => [
        'type' => GraphQL::type('QuestionType'),
        'description' => 'Details of the question',
        'resolve' => function ($root, $args) {
          return Question::find($root['question_id']);
        },
      ],
      'response' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'Response is a string',
      ],
      'created_at' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'created_at',
      ],
    ];
  }
}
