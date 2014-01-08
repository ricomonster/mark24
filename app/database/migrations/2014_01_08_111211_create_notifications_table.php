<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('notification_id');
			$table->integer('receiver_id')->unsigned();
			$table->foreign('receiver_id')->references('id')->on('users');
			$table->integer('sender_id')->unsigned();
			$table->foreign('sender_id')->references('id')->on('users');
			$table->string('notification_type', 20);
			$table->integer('involved_id')->unsigned()->default(0);
			$table->bigInteger('notification_timestamp')->unsigned();
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
		Schema::drop('notifications');
	}

}
