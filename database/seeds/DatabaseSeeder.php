<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\DomainController;
use App\Trust;
use App\Site;
use App\User;
use App\SiteUser;
use App\Role;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		SiteUser::truncate();
		DomainController::truncate();
		Trust::truncate();
		Site::truncate();
		User::truncate();
		Role::truncate();


		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

		Model::unguard();
		$this->call('DomainControllersTableSeeder');
		$this->call('TrustsTableSeeder');
		$this->call('SitesTableSeeder');
		$this->call('UsersTableSeeder');
		$this->call('RolesTableSeeder');
		$this->call('SiteUserTableSeeder');
	}

}
