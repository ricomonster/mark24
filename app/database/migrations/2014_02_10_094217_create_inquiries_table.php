<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inquiries', function(Blueprint $table)
		{
			$table->increments('inquiry_id');
            $table->integer('inquirer_id')->unsigned();
            $table->foreign('inquirer_id')
                ->references('id')
                ->on('users');
            $table->integer('involved_id')->unsigned();
            $table->string('type', 50);
            $table->integer('status')->default(1)->unsigned();
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
		Schema::drop('inquiries');
	}

}