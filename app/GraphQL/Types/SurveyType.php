<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Survey;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SurveyType extends GraphQLType
{
  protected $attributes = [
    'name' => 'SurveyType',
    'description' => 'A type',
    'model' => Survey::class
  ];

  public function fields(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::int()),
        'description' => 'Id of a particular book',
      ],
      'name' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'The name is a string',
      ],
      'exists_in_questions' => [
        'type' => Type::getNullableType(Type::int()),
        'description' => '',
      ],
    ];
  }
}
