<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdentificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('identification', function(Blueprint $table)
		{
			$table->increments('identification_id');
			$table->integer('question_id')->unsigned();
			$table->foreign('question_id')
				->references('question_id')
				->on('questions');
			$table->string('answer')->nullable();
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
		Schema::drop('identification');
	}

}