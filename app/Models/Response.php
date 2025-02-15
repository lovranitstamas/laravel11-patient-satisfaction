<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
  protected $fillable = [
    'submitter_name',
    'email',
    'survey_id',
    'question_id',
    'response'
  ];
}
