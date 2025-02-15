<?php

declare(strict_types=1);

namespace App\GraphQL\Types\Pagination;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PaginationLinkType extends GraphQLType
{

  //Not in direct using
  protected $attributes = [
    'name' => 'PaginationLink',
    'description' => 'A type'
  ];

  public function fields(): array
  {
    return [
      'url' => [
        'type' => Type::string()
      ],
      'label' => [
        'type' => Type::nonNull(Type::string())
      ],
      'active' => [
        'type' => Type::nonNull(Type::int())
      ]
    ];
  }
}
