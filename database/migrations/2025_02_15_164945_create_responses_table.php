<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('responses', function (Blueprint $table) {

      $table->charset = 'utf8';
      $table->collation = 'utf8_general_ci';
      $table->engine = 'InnoDB';

      $table->id();
      $table->string('submitter_name')->nullable();
      $table->string('email')->nullable();
      $table->unsignedBigInteger('survey_id');
      $table->unsignedBigInteger('question_id');
      $table->string('response');
      $table->timestamps();

      $table->foreign('survey_id')->references('id')->on('surveys');
      $table->foreign('question_id')->references('id')->on('questions');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {

    Schema::table('responses', function (Blueprint $table) {
      $table->dropForeign('responses_survey_id_foreign');
      $table->dropColumn('survey_id');
      $table->dropForeign('responses_question_id_foreign');
      $table->dropColumn('question_id');
    });

    Schema::dropIfExists('responses');
  }
};
