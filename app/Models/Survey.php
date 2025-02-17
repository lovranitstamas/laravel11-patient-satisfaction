<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
  ];

  protected $appends = [
    'exists_in_responses'
  ];

  public function getExistsInResponsesAttribute()
  {
    $questions = $this->questions;

    $existsInResponses = Response::whereIn('question_id', $questions->pluck('id'))->exists();

    return (int)$existsInResponses;
  }

  public function questions(): HasMany
  {
    return $this->hasMany(Question::class);
  }
}
