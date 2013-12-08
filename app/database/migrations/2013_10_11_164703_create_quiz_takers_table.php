<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizTakersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('quiz_takers', function(Blueprint $table)
		{
			$table->increments('quiz_taker_id');
			$table->integer('user_id')
				->unsigned();
			$table->foreign('user_id')
				->references('id')
				->on('users');

			$table->integer('quiz_id')
				->unsigned();
			$table->foreign('quiz_id')
				->references('quiz_id')
				->on('quiz');

			$table->string('status', 15)
				->default('NOT YET PASSED');
			$table->integer('score')
				->unsigned();
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
		Schema::drop('quiz_takers');
	}

}
