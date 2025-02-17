<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Survey;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class DeleteSurveyMutation extends Mutation
{
  protected $attributes = [
    'name' => 'deleteSurveyMutation',
    'description' => 'A mutation'
  ];

  public function type(): Type
  {
    return GraphQL::type('SurveyType');
  }

  public function args(): array
  {
    return [
      'id' => [
        'name' => 'id',
        'type' => Type::nonNull(Type::int()),
      ],
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
  {
    /*
    $fields = $getSelectFields();
    $select = $fields->getSelect();
    $with = $fields->getRelations();

    return [];
    */

    $survey = Survey::findOrFail($args['id']);

    $deleted = $survey->delete();

    throw_if(!$deleted,
      new Error('A kérdőív törlése nem sikerült'));

    return $survey;
  }
}
