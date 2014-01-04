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
			$table->string('name', 255);
			$table->string('salutation', 5);
			$table->string('firstname', 100);
			$table->string('lastname', 100);
			$table->string('username', 30);
			$table->string('email', 50);
			$table->string('password', 255);
			$table->string('hashed_id', 70);
			$table->string('avatar', 255)->default('default_avatar.png');
			$table->string('avatar_small', 255)->default('default_avatar.png');
			$table->string('avatar_normal', 255)->default('default_avatar.png');
			$table->string('avatar_large', 255)->default('default_avatar.png');
			$table->integer('forum_posts')->unsigned();
			$table->bigInteger('online_timestamp')->unsigned();
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
