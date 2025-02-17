<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Response;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class StoreResponseMutation extends Mutation
{
  protected $attributes = [
    'name' => 'storeResponseMutation',
    'description' => 'A mutation'
  ];

  public function type(): Type
  {
    return GraphQL::type('ResponseType');
  }

  public function args(): array
  {
    return [
      'submitter_name' => [
        'name' => 'submitter_name',
        'type' => Type::getNullableType(Type::string()),
      ],
      'email' => [
        'name' => 'email',
        'type' => Type::getNullableType(Type::string()),
      ],
      'answers' => [
        'name' => 'answers',
        'type' => Type::listOf(Type::listOf(Type::string())),
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

    return $this->createResponseByArgs($args);
  }

  /**
   * @throws Error
   */
  private function createResponseByArgs(array $args): Response
  {
    $this->checkRestrictedData($args);

    return $this->storeResponseData($args);
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

  }

  /**
   * Store inventory base data
   *
   * @param array $args
   * @return Response
   */
  private function storeResponseData(array $args): Response
  {

    $createdResponse = null;

    $answers = $args['answers'];

    foreach ($answers as $answer) {

      $question_id = (int)$answer[0];
      $response = $answer[1];

      $args['question_id'] = $question_id;
      $args['response'] = $response;

      $item = Response::create($args);

      if (!$createdResponse) {
        $createdResponse = $item;
      }

    }

    return $createdResponse;

  }
}
