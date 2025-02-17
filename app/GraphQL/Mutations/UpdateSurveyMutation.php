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

class UpdateSurveyMutation extends Mutation
{
  protected $attributes = [
    'name' => 'updateSurveyMutation',
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
      'name' => [
        'name' => 'name',
        'type' => Type::nonNull(Type::string()),
        'rules' => ['required'],
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

    throw_if(!$this->updateSurveyByArgs($survey, $args),
      new Error('A kérdés módosítása nem sikerült'));

    return $survey;
  }

  private function updateSurveyByArgs(Survey $survey, array $args): bool
  {

    return $survey->update($args);
  }
}
