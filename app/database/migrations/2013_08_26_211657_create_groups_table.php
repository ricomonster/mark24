<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('groups', function(Blueprint $table)
		{
			$table->increments('group_id');
			$table->integer('owner_id')->unsigned();
			$table->foreign('owner_id')->references('id')->on('users');
			$table->string('group_code', 6);
			$table->string('group_name', 30);
			$table->string('group_description', 250);
			$table->string('group_size', 20);
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
		Schema::drop('groups');
	}

}
