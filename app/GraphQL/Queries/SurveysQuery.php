<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Survey;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class SurveysQuery extends Query
{
  protected $attributes = [
    'name' => 'surveysQuery',
    'description' => 'A query'
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type('SurveyType'));
  }

  public function args(): array
  {
    return [

    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
  {

    /** @var SelectFields $fields */
    /*
    $fields = $getSelectFields();
    $select = $fields->getSelect();
    $with = $fields->getRelations();
    */

    return Survey::all();
  }
}
