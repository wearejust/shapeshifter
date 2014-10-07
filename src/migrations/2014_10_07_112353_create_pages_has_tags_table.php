<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesHasTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages_has_tags', function($table)
		{
			$table->increments('id');
			$table->integer('page_id')->references('id')->on('pages')->onDelete('cascade');
			$table->integer('tag_id')->references('id')->on('tags')->onDelete('cascade');
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
		Schema::drop('pages_has_tags');
	}

}
