<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainControllersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('domain_controllers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('account_suffix', 50);
			$table->string('base_dn', 50);
			$table->string('domain_controller', 50);
			$table->string('admin_username', 60);
			$table->string('admin_password');
			$table->boolean('use_ssl');
			$table->string('ad_port', 5);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('domain_controllers');
	}

}
