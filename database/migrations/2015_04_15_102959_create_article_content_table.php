<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleContentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('article_content', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('article_id')->unsigned();
			$table->foreign('article_id')->references('id')->on('articles');
			$table->string('title');
			$table->text('content');
			$table->timestamp('approved_at');
			$table->integer('approved_by')->unsigned();
			$table->foreign('approved_by')->references('id')->on('users');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('article_content');
	}

}
