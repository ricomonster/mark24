<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatConversationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chat_conversations', function(Blueprint $table)
		{
			$table->increments('chat_conversation_id');
			$table->integer('conversation_id')->unsigned();
			$table->foreign('conversation_id')
				->references('conversation_id')
				->on('conversations');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->text('message');
			$table->bigInteger('chat_timestamp')->unsigned();
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
		Schema::table('chat_conversations', function(Blueprint $table)
		{
			//
		});
	}

}
