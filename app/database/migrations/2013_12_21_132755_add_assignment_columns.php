<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssignmentColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('posts', function(Blueprint $table)
		{
			$table->integer('assignment_id')
				->unsigned()
				->nullable()
				->after('quiz_due_date');
			$table->date('assignment_due_date')
				->nullable()
				->after('assignment_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('posts', function(Blueprint $table)
		{
			$table->dropColumn('assignment_id');
			$table->dropColumn('assignment_due_date');
		});
	}

}
