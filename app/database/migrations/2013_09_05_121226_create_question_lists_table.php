<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('question_lists', function(Blueprint $table)
		{
			$table->increments('question_list_id');
			$table->integer('quiz_id')->unsigned();
			$table->foreign('quiz_id')
				->references('quiz_id')
				->on('quiz');
			$table->integer('question_id')->unsigned();
			$table->foreign('question_id')
				->references('question_id')
				->on('questions');
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
		Schema::drop('question_lists');
	}

}
