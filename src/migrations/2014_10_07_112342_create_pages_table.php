<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function($table)
		{
			$table->increments('id');
			$table->integer('parent_id')->nullable();
			$table->boolean('checkbox');
			$table->string('title')->nullable();
			$table->string('email')->nullable();
			$table->date('date')->nullable();
			$table->datetime('datetime')->nullable();
			$table->string('file')->nullable();
			$table->string('dropdown')->nullable();
			$table->string('radio')->nullable();
			$table->text('body')->nullable();
			$table->text('textarea')->nullable();
			$table->string('video')->nullable();
			$table->string('color')->nullable();
			$table->string('forced')->nullable();
			$table->text('ckeditor')->nullable();
			$table->text('sortorder')->nullable();
			$table->integer('sortorder')->nullable();
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
		Schema::drop('pages');
	}

}
