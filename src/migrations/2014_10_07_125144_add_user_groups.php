<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserGroups extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Sentry::createGroup(array(
			                    'name'        => 'Just Werknemer',
			                    'permissions' => array(
				                    'superuser' => 1
			                    ),
		                    ));

		Sentry::createGroup(array(
			                    'name'        => 'Administrators',
			                    'permissions' => array(
				                    'admin.users.index' => 1
			                    ),
		                    ));


		Sentry::createGroup(array(
			                    'name'        => 'Moderators',
			                    'permissions' => array(
				                    'admin.groups.update' => 1,
				                    'admin.groups.destroy' => 1,
				                    'admin.users.update' => 1,
				                    'admin.users.show' => 1
			                    ),
		                    ));

		Sentry::createGroup(array(
			                    'name'        => 'Testgroep',
			                    'permissions' => array(),
		                    ));

		Sentry::createGroup(array(
			                    'name'        => 'Groep',
			                    'permissions' => array(
				                    'groups.destroy' => 1,
				                    'groups.update' => 1
			                    ),
		                    ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('cms_groups')->truncate();
	}

}
