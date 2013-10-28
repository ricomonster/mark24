<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrueFalseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('true_false', function(Blueprint $table)
		{
			$table->increments('true_false_id');
			$table->integer('question_id')
				->unsigned();

			$table->foreign('question_id')
				->references('question_id')
				->on('questions');

			$table->string('answer', 6)
				->default('TRUE');
				
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
		Schema::drop('true_false');
	}

}
