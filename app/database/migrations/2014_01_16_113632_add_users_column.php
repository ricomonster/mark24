<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->string('country', 50)->nullable()->after('password');
			$table->text('description')->nullable()->after('country');
			$table->text('what_to_learn')->nullable()->after('description');
			$table->text('goals')->nullable()->after('what_to_learn');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('country');
			$table->dropColumn('description');
			$table->dropColumn('what_to_learn');
			$table->dropColumn('goals');
		});
	}

}
