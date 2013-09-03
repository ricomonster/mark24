<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_type')->unsigned();
			$table->string('name');
			$table->string('salutation', 5)->nullable();
			$table->string('firstname');
			$table->string('lastname');
			$table->string('username', 30);
			$table->string('email', 50);
			$table->string('password');
			$table->string('avatar')->default('default_avatar.png');
			$table->string('avatar_small')->default('default_avatar.png');
			$table->string('avatar_normal')->default('default_avatar.png');
			$table->string('avatar_large')->default('default_avatar.png');

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
		Schema::drop('users');
	}

}
