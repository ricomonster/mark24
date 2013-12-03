<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('conversations', function(Blueprint $table)
		{
			$table->increments('conversation_id');
			$table->integer('chat_conversation_id')->unsigned();
			$table->foreign('chat_conversation_id')
				->references('chat_conversation_id')
				->on('chat_conversations');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
				->references('id')
				->on('users');
			$table->text('message');
			$table->bigInteger('chat_timestamp');
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
		Schema::drop('conversations');
	}

}
