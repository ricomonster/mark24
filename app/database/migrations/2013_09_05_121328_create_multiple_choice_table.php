<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultipleChoiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('multiple_choice', function(Blueprint $table)
		{
			$table->increments('multiple_choice_id');
			$table->integer('question_id')
				->unsigned();

			$table->foreign('question_id')
				->references('question_id')
				->on('questions');

			$table->string('choice_text')
				->nullable();
				
			$table->string('is_answer', 6)
				->default('FALSE');

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
		Schema::drop('multiple_choice');
	}

}
