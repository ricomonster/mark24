<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('quiz', function(Blueprint $table)
		{
			$table->increments('quiz_id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->string('title', 50);
			$table->text('description');
			$table->integer('time_limit')->unsigned();
			$table->integer('total_score')->unsigned();
			$table->string('status', 20)->default('ACTIVE');
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
		Schema::drop('quiz');
	}

}
