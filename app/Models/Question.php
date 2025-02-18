<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
  use HasFactory;

  protected $fillable = [
    'survey_id',
    'question',
  ];

  protected $appends = [
    'exists_in_responses'
  ];

  /**
   * Relation: Survey of this Question
   *
   * @return BelongsTo
   */
  public function survey(): BelongsTo
  {
    return $this->belongsTo(
      Survey::class,
      'survey_id',
      'id'
    );
  }

  public function responses(): HasMany
  {
    return $this->hasMany(Response::class, 'question_id');
  }

  public function getExistsInResponsesAttribute()
  {
    return (int)Response::where('question_id', $this->id)->exists();
  }
}
