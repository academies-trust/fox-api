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
			$table->foreign('site_id')->references('id')->on('sites');
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('role_id')->unsigned();
			$table->foreign('role_id')->references('id')->on('roles');
			$table->unique(['user_id', 'site_id', 'role_id']);
		});

		Schema::create('group_user', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('group_id')->unsigned();
			$table->foreign('group_id')->references('id')->on('groups');
			$table->integer('permission_id')->unsigned();
			$table->foreign('permission_id')->references('id')->on('permissions');
		});

		Schema::create('module_site', function(Blueprint $table)
		{
			$table->integer('site_id')->unsigned()->index();
			$table->foreign('site_id')->references('id')->on('sites');
			$table->integer('module_id')->unsigned();
			$table->foreign('module_id')->references('id')->on('modules');
			$table->unique(['site_id', 'module_id']);
		});

		Schema::create('module_group', function(Blueprint $table)
		{
			$table->integer('group_id')->unsigned()->index();
			$table->foreign('group_id')->references('id')->on('groups');
			$table->integer('module_id')->unsigned();
			$table->foreign('module_id')->references('id')->on('modules');
			$table->unique(['group_id', 'module_id']);
		});

		Schema::create('group_resource', function(Blueprint $table)
		{
			$table->integer('group_id')->unsigned()->index();
			$table->foreign('group_id')->references('id')->on('groups');
			$table->integer('resource_id')->unsigned();
			$table->foreign('resource_id')->references('id')->on('resources');
			$table->unique(['group_id', 'resource_id']);
		});

		Schema::create('resourceables', function(Blueprint $table)
		{
			$table->integer('resource_id')->unsigned();
			$table->foreign('resource_id')->references('id')->on('resources');
			$table->integer('resourceable_id')->unsigned();
			$table->string('resourceable_type');
		});

		Schema::create('marking_scheme_site', function(Blueprint $table)
		{
			$table->integer('site_id')->unsigned();
			$table->foreign('site_id')->references('id')->on('sites');
			$table->integer('marking_scheme_id')->unsigned();
			$table->foreign('marking_scheme_id')->references('id')->on('marking_schemes');
			$table->unique(['site_id', 'marking_scheme_id']);
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
		Schema::drop('resourceables');
		Schema::drop('marking_scheme_site');
	}

}
