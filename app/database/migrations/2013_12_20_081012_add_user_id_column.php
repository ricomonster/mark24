<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('file_attached', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->after('post_id');
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('file_attached', function(Blueprint $table)
		{
			$table->dropColumn('user_id');
		});
	}

}
