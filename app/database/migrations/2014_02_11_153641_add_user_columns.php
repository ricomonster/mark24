<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
		    $table->string('hometown')->nullable()->after('country');
            $table->string('current_place')->nullable()->after('hometown');
			$table->date('birthday')->nullable()->after('current_place');
			$table->string('tagline')->nullable()->after('birthday');
            $table->text('description')->nullable()->after('tagline');
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
			$table->dropColumn('birthday');
            $table->dropColumn('tagline');
            $table->dropColumn('description');
            $table->dropColumn('hometown');
            $table->dropColumn('current_place');
        });
	}

}