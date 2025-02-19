<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Helpers\PaginationHelper;
use App\Models\Survey;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class SurveysBasedOnPaginationQuery extends Query
{
	protected $attributes = [
		'name' => 'surveysBasedOnPaginationQuery',
		'description' => 'A query'
	];
	
	public function type(): Type
	{
		return GraphQL::paginate('SurveyType');
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
		/*
		$fields = $getSelectFields();
		$select = $fields->getSelect();
		$with = $fields->getRelations();
		*/
		
		$query = Survey::query();
		
		$query = $query
			->where(function ($query) use ($args) {
				if (isset($args['search'])) {
					$query->where('name', 'LIKE', "%{$args['search']}%");
				}
				return $query;
			});
		
		$query = $query->orderByDesc('created_at');
		
		$totalResults = (clone $query)->toBase()->getCountForPagination();
		
		$currentPage = (!empty($args['search']) && $totalResults < ($args['per_page'] ?? 25)) ? 1 :
			(
			($args['current_page'] ?? 1) > ceil($totalResults / ($args['per_page'] ?? 25))
				? 1
				: ($args['current_page'] ?? 1)
			);
		
		return $query->paginate($args['per_page'] ?? 25, ['*'], 'page', $currentPage);
	}
}
