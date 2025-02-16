<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Response extends Model
{
  protected $fillable = [
    'submitter_name',
    'email',
    'survey_id',
    'question_id',
    'response'
  ];

  /**
   * Relation: Survey of this Response
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
