<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group_members', function(Blueprint $table)
		{
			$table->integer('group_member_id')->unsigned();
			$table->foreign('group_member_id')->references('id')->on('users');
			$table->integer('group_id')->unsigned();
			$table->foreign('group_id')->references('group_id')->on('groups');
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
		Schema::drop('group_members');
	}

}
