<?php

declare(strict_types=1);

namespace App\GraphQL\Types\Pagination;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type as GraphQLType;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CustomPagination extends ObjectType
{
	public function __construct(string $typeName, string $customName = null)
	{
		
		$name = $customName ?: $typeName . 'Pagination';
		
		$config = [
			'name' => $name,
			'fields' => $this->getPaginationFields($typeName),
		];
		
		$underlyingType = GraphQL::type($typeName);
		
		if (isset($underlyingType->config['model'])) {
			$config['model'] = $underlyingType->config['model'];
		}
		
		parent::__construct($config);
	}
	
	protected function getPaginationFields(string $typeName): array
	{
		return [
      //db response keys
      //default
			'data' => [
				'type' => GraphQLType::listOf(GraphQL::type($typeName)),
				'description' => 'List of items on the current page',
				'resolve' => function (LengthAwarePaginator $data): Collection {
					return $data->getCollection();
				},
			],
      //default
			'current_page' => [ // current_page = page
				'type' => GraphQLType::nonNull(GraphQLType::int()),
				'description' => 'Current page of the cursor',
				'resolve' => function (LengthAwarePaginator $data): int {
					return $data->currentPage();
				},
				'selectable' => false,
			],
      //default
      'from' => [
        'type' => GraphQLType::int(),
        'description' => 'Number of the first item returned',
        'resolve' => function (LengthAwarePaginator $data): int {
          return $data->firstItem() ?? 1;
        },
        'selectable' => false,
      ],
      //default
			'last_page' => [
				'type' => GraphQLType::nonNull(GraphQLType::int()),
				'description' => 'The last page (number of pages)',
				'resolve' => function (LengthAwarePaginator $data): int {
					return $data->lastPage();
				},
				'selectable' => false,
			],
      'next_page_url' => [
        'type' => GraphQLType::string(),
        'description' => 'The next page url',
        'resolve' => function (LengthAwarePaginator $data): string {
          return $data->nextPageUrl() ?? "";
        },
        'selectable' => false,
      ],
      //default
			'per_page' => [ // limit = per_page
				'type' => GraphQLType::nonNull(GraphQLType::int()),
				'description' => 'Number of items returned per page',
				'resolve' => function (LengthAwarePaginator $data): int {
					return $data->perPage();
				},
				'selectable' => false,
			],
      'prev_page_url' => [
        'type' => GraphQLType::string(),
        'description' => 'The next page url',
        'resolve' => function (LengthAwarePaginator $data): string {
          return $data->previousPageUrl() ?? "";
        },
        'selectable' => false,
      ],
      //default
      'to' => [
        'type' => GraphQLType::int(),
        'description' => 'Number of the last item returned',
        'resolve' => function (LengthAwarePaginator $data): int {
          return $data->lastItem() ?? 1;
        },
        'selectable' => false,
      ],
      //default
			'total' => [
				'type' => GraphQLType::nonNull(GraphQLType::int()),
				'description' => 'Number of total items selected by the query',
				'resolve' => function (LengthAwarePaginator $data): int {
					return $data->total();
				},
				'selectable' => false,
			],
      //end of db response
      //custom
			'links' => [
				'type' => GraphQLType::listOf(GraphQL::type('PaginationLink')),
				'resolve' => function (LengthAwarePaginator $data): Collection {
					return $data->linkCollection();
				},
				'selectable' => false,
			],

      //default
			'has_more_pages' => [
				'type' => GraphQLType::nonNull(GraphQLType::boolean()),
				'description' => 'Determines if cursor has more pages after the current page',
				'resolve' => function (LengthAwarePaginator $data): bool {
					return $data->hasMorePages();
				},
				'selectable' => false,
			],
		];
	}
}
