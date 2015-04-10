<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinkTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('site_user', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('site_id')->unsigned();
			$table->integer('user_id')->unsigned()->index();
			$table->integer('role_id')->unsigned();
			$table->unique('user_id', 'site_id', 'role_id');
		});

		Schema::create('group_user', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned();
			$table->integer('group_id')->unsigned();
			$table->unique('user_id', 'group_id');
			$table->integer('permission_id')->unsigned();
		});

		Schema::create('module_site', function(Blueprint $table)
		{
			$table->integer('site_id')->unsigned()->index();
			$table->integer('module_id')->unsigned();
			$table->unique('site_id', 'module_id');
		});

		Schema::create('module_group', function(Blueprint $table)
		{
			$table->integer('group_id')->unsigned()->index();
			$table->integer('module_id')->unsigned();
			$table->unique('group_id', 'module_id');
		});

		Schema::create('group_resource', function(Blueprint $table)
		{
			$table->integer('group_id')->unsigned()->index();
			$table->integer('resource_id')->unsigned();
			$table->unique('group_id', 'resource_id');
		});

		Schema::create('post_resource', function(Blueprint $table)
		{
			$table->integer('post_id')->unsigned();
			$table->integer('resource_id')->unsigned();
			$table->unique('post_id', 'resource_id');
		});

		Schema::create('marking_scheme_site', function(Blueprint $table)
		{
			$table->integer('site_id')->unsigned();
			$table->integer('marking_scheme_id')->unsigned();
			$table->unique('site_id', 'marking_scheme_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('site_user');
		Schema::drop('group_user');
		Schema::drop('module_site');
		Schema::drop('module_group');
		Schema::drop('group_resource');
		Schema::drop('post_resource');
		Schema::drop('marking_scheme_site');
	}

}
