<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Question;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class UpdateQuestionMutation extends Mutation
{
  protected $attributes = [
    'name' => 'updateQuestionMutation',
    'description' => 'A mutation'
  ];

  public function type(): Type
  {
    return GraphQL::type('QuestionType');
  }

  public function args(): array
  {
    return [
      'id' => [
        'name' => 'id',
        'type' => Type::nonNull(Type::int()),
      ],
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

  public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
  {
    /*
    $fields = $getSelectFields();
    $select = $fields->getSelect();
    $with = $fields->getRelations();

    return [];
    */

    $questionnaire = Question::findOrFail($args['id']);

    throw_if(!$this->updateQuestionnaireByArgs($questionnaire, $args),
      new Error('A kérdés módosítása nem sikerült'));

    return $questionnaire->load(['survey']);
  }

  private function updateQuestionnaireByArgs(Question $question, array $args): bool
  {
    return $question->update($args);
  }
}
