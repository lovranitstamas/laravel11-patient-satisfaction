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

class StoreSurveyMutation extends Mutation
{
  protected $attributes = [
    'name' => 'storeSurveyMutation',
    'description' => 'A mutation'
  ];

  public function type(): Type
  {
    return GraphQL::type('SurveyType');
  }

  public function args(): array
  {
    return [
      'name' => [
        'name' => 'name',
        'type' => Type::nonNull(Type::string()),
        'rules' => ['required']
      ],
    ];
  }

  /**
   * @throws Error
   */
  public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
  {
    /*
      $fields = $getSelectFields();
      $select = $fields->getSelect();
      $with = $fields->getRelations();

      return [];
    */

    return $this->createQuestionnaireByArgs($args);
  }

  /**
   * @throws Error
   */
  private function createQuestionnaireByArgs(array $args): Survey
  {
    $this->checkRestrictedData($args);

    return $this->storeSurveyData($args);
  }

  /**
   * Check restricted data
   *
   * @param $args
   * @return void
   * @throws Error
   */
  private function checkRestrictedData($args): void
  {

    throw_if(!is_null(Survey::where('name', $args['name'])->first()),
      new Error('A lementendő kérdőív már létezik'));
  }

  /**
   * Store inventory base data
   *
   * @param array $args
   * @return Survey
   */
  private function storeSurveyData(array $args): Survey
  {
    return Survey::create($args);
  }
}
