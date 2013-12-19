<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStorageFileNameColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('file_library', function(Blueprint $table)
		{
			$table->text('file_storage_name')->after('file_name');
			$table->string('file_thumbnail', 100)->after('file_path');
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
			$table->dropColumn('file_storage_name');
			$table->dropColumn('file_thumbnail');
		});
	}

}
