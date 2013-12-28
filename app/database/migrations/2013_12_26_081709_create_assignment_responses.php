<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentResponses extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assignment_responses', function(Blueprint $table)
		{
			$table->increments('assignment_response_id');
			$table->integer('assignment_id')->unsigned();
			$table->foreign('assignment_id')->references('assignment_id')->on('assignments');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->string('status', 20)->default('AWAITING GRADE');
			$table->text('response');
			$table->bigInteger('response_timestamp')->unsigned();
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
		Schema::drop('assignment_responses');
	}

}
