<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Just\Shapeshifter\Core\Models\Settings::create(['key' => 'fb:app_id', 'value' => '']);
		Just\Shapeshifter\Core\Models\Settings::create(['key' => 'fb:admins', 'value' => '']);
		Just\Shapeshifter\Core\Models\Settings::create(['key' => 'og:site_name', 'value' => 'Just']);
		Just\Shapeshifter\Core\Models\Settings::create(['key' => 'og:image', 'value' => '/apple-touch-icon-precomposed.png']);
		Just\Shapeshifter\Core\Models\Settings::create(['key' => 'msapplication-TileImage', 'value' => '/apple-touch-icon-precomposed.png']);
		Just\Shapeshifter\Core\Models\Settings::create(['key' => 'msapplication-TileColor', 'value' => '#ffffff']);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('settings')->truncate();
	}

}
