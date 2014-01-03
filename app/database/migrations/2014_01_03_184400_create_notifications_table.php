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
			$table->integer('recipient_id')->unsigned();
			$table->foreign('recipient_id')->references('id')->on('users');
			$table->string('notification_type', 20);
			$table->integer('notification_reference_id')->unsigned();
			$table->integer('referral_id')->unsigned();
			$table->string('seen', 5)->default('false');
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
