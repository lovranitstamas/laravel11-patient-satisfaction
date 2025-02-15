<?php

namespace App\Helpers;

use GraphQL\Type\Definition\Type;

class PaginationHelper
{
  static function args(): array
  {

    //send in query
    return [
      'current_page' => [
        'name' => 'current_page',
        'type' => Type::int(),
      ],
      'per_page' => [
        'name' => 'per_page',
        'type' => Type::int(),
      ],

      'search' => [
        'name' => 'search',
        'type' => Type::string(),
      ],
      'orderBy' => [
        'name' => 'orderBy',
        'type' => Type::string(),
      ],
    ];
  }
}
