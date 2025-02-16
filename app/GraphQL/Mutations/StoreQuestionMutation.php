<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Question;
use App\Models\Survey;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class StoreQuestionMutation extends Mutation
{
  protected $attributes = [
    'name' => 'storeQuestionMutation',
    'description' => 'A mutation'
  ];

  public function type(): Type
  {
    return GraphQL::type('QuestionType');
  }

  public function args(): array
  {
    return [
      'survey_id' => [
        'name' => 'survey_id',
        'type' => Type::nonNull(Type::int()),
        'rules' => ['required']
      ],
      'question' => [
        'name' => 'question',
        'type' => Type::nonNull(Type::string()),
        'rules' => ['required'],
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
  private function createQuestionnaireByArgs(array $args): Question
  {
    $this->checkRestrictedData($args);

    return $this->storeQuestionnaireData($args);
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

    throw_if(is_null(Survey::find($args['survey_id'])),
      new Error('A beállított kérdőív nem létezik'));
  }

  /**
   * Store inventory base data
   *
   * @param array $args
   * @return Question
   */
  private function storeQuestionnaireData(array $args): Question
  {

    $clientInventory = Question::create($args);

    return $clientInventory->load(['survey']);
  }
}
