<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChatTimestampColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('chat_conversations', function(Blueprint $table)
		{
			$table->bigInteger('chat_timestamp')->unsigned()->after('message');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('chat_conversations', function(Blueprint $table)
		{
			$table->dropColumn('chat_timestamp');
		});
	}

}
