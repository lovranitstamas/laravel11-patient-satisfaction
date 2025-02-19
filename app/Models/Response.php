<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

//TODO modify model name
class Response extends Model
{
  protected $fillable = [
    'submitter_name',
    'gender',
    'email',
    'survey_id',
    'question_id',
    'response'
  ];

  /**
   * Relation: Survey of this Response
   *
   * @return HasOneThrough
   */
  public function survey(): HasOneThrough
  {
    return $this->hasOneThrough(Survey::class, Question::class, 'id', 'id', 'question_id', 'survey_id');
  }

  /**
   * Relation: Question of this Response
   *
   * @return BelongsTo
   */
  public function question(): BelongsTo
  {
    return $this->belongsTo(
      Question::class,
      'question_id',
      'id'
    );
  }
}
