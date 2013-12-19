<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNumberOfDownloadsColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('file_library', function(Blueprint $table)
		{
			$table->integer('download_count')->after('file_thumbnail')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('file_library', function(Blueprint $table)
		{
			$table->dropColumn('download_count');
		});
	}

}
