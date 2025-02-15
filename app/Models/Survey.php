<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
  use HasFactory;

  protected $fillable = [
    'submitter_name',
    'email',
    'survey_id',
    'question_id',
    'response'
  ];
}
