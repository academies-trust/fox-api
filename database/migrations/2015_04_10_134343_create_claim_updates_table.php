<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimUpdatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('claim_updates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('claim_id')->unsigned();
			$table->foreign('claim_id')->references('id')->on('claims');
			$table->text('content')->nullable();
			$table->integer('status_id')->unsigned();
			$table->foreign('status_id')->references('id')->on('claim_statuses');
			$table->softDeletes();
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
		Schema::drop('claim_updates');
	}

}
