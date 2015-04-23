<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupSiteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group_site', function(Blueprint $table)
		{
			$table->integer('group_id')->unsigned();
			$table->foreign('group_id')->references('id')->on('groups');
			$table->integer('site_id')->unsigned();
			$table->foreign('site_id')->references('id')->on('sites');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('group_site');
	}

}
