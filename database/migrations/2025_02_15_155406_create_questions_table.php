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
    Schema::create('questions', function (Blueprint $table) {

      $table->charset = 'utf8';
      $table->collation = 'utf8_general_ci';
      $table->engine = 'InnoDB';

      $table->id();
      $table->unsignedBigInteger('survey_id');
      $table->string('question');
      $table->timestamps();

      $table->foreign('survey_id')->references('id')->on('surveys');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {

    Schema::table('questions', function (Blueprint $table) {
      $table->dropForeign('questions_survey_id_foreign');
      $table->dropColumn('survey_id');
    });

    Schema::dropIfExists('questions');
  }
};
