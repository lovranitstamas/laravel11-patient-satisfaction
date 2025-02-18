<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Helpers\PaginationHelper;
use App\Models\Response;
use App\Models\Survey;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ResponsesQuery extends Query
{
  protected $attributes = [
    'name' => 'responsesQuery',
    'description' => 'A query'
  ];

  public function type(): Type
  {
    return GraphQL::paginate('ResponseType');
  }

  public function args(): array
  {
    return [
      ...PaginationHelper::args(),
      'survey_id' => [
        'name' => 'survey_id',
        'type' => Type::getNullableType(Type::int()),
      ],
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
  {

    /** @var SelectFields $fields */
    /*
    $fields = $getSelectFields();
    $select = $fields->getSelect();
    $with = $fields->getRelations();

    return [
        'The surveys works',
    ];
    */

    $query = Response::query();

    $query = $query
      ->where(function ($query) use ($args) {
        if (isset($args) && isset($args['survey_id'])) {
          $query->whereHas('question', function ($query) use ($args) {
            $query->where('survey_id', $args['survey_id']);
          });
        } else {
          $surveyId = Survey::whereHas('questions.responses')
            ->orderBy('created_at')
            ->pluck('id')
            ->first();

          if ($surveyId) {
            $query->whereHas('question', function ($query) use ($surveyId) {
              $query->where('survey_id', $surveyId);
            });
          }

        }
        return $query;
      })
      ->where(function ($query) use ($args) {
        if (isset($args['search'])) {
          $query->where('response', 'LIKE', "%{$args['search']}%");
        }
        return $query;
      });

    $query = $query->orderByDesc('created_at');

    $totalResults = (clone $query)->toBase()->getCountForPagination();

    $currentPage = (!empty($args['search']) && $totalResults < ($args['per_page'] ?? 25)) ? 1 :
      (
        // Ha a jelenlegi oldal és a per_page szorzata nagyobb, mint a találatok száma, akkor az első oldal
        ($args['current_page'] ?? 1) > ceil($totalResults / ($args['per_page'] ?? 25))
        ? 1
        : ($args['current_page'] ?? 1)
      );

    return $query->paginate($args['per_page'] ?? 25, ['*'], 'page', $currentPage);
  }
}
