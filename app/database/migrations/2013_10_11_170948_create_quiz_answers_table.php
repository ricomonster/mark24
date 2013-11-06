<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('quiz_answers', function(Blueprint $table)
		{
			$table->increments('quiz_answer_id');
			$table->integer('quiz_taker_id')
				->unsigned();
			$table->foreign('quiz_taker_id')
				->references('quiz_taker_id')
				->on('quiz_takers');

			$table->integer('question_id')
				->unsigned();
			$table->foreign('question_id')
				->references('question_id')
				->on('questions');

			$table->integer('multiple_choice_answer')
				->unsigned();
			$table->string('true_false_answer', 6);
			$table->text('short_answer_text');

			$table->string('is_correct', 5);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('quiz_answers');
	}

}
