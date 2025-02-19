<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Question;
use App\Models\SurveyResponse;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ResponseType extends GraphQLType
{
  protected $attributes = [
    'name' => 'ResponseType',
    'description' => 'A type',
    'model' => SurveyResponse::class
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
      'gender' => [
        'type' => Type::getNullableType(Type::string()),
        'description' => 'The gender is a string',
      ],
      'email' => [
        'type' => Type::getNullableType(Type::string()),
        'description' => 'The email is a string',
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
