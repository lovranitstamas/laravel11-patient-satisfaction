<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Helpers\PaginationHelper;
use App\Models\Question;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class QuestionsQuery extends Query
{
  protected $attributes = [
    'name' => 'questionsQuery',
    'description' => 'A query'
  ];

  public function type(): Type
  {
    return GraphQL::paginate('QuestionType');
  }

  public function args(): array
  {
    return [
      ...PaginationHelper::args(),
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
  {

    /** @var SelectFields $fields */

    $query = Question::query();

    $query = $query->where(function ($query) use ($args) {
      if (isset($args['search'])) {
        $query->where('name', 'LIKE', "%{$args['search']}%");
      }
      return $query;
    });

    /*
    $fields = $getSelectFields();
    $select = $fields->getSelect();
    $with = $fields->getRelations();

    return [
        'The surveys works',
    ];
    */

    return $query->paginate($args['per_page'] ?? 25, ['*'], 'page', $args['current_page'] ?? 1);
  }
}
