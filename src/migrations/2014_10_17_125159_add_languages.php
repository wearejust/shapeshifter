<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Just\Shapeshifter\Core\Models\Language;

class AddLanguages extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Language::create([
			                 'short_code' => 'nl',
			                 'active'     => true,
			                 'name'       => 'Nederlands',
			                 'sortorder'  => 1
		                 ]);

		Language::create([
			                 'short_code' => 'en',
			                 'active'     => false,
			                 'name'       => 'English',
			                 'sortorder'  => 2
		                 ]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down ()
	{
		DB::table('languages')->truncate();
	}

}
