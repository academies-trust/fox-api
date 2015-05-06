<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\DomainController;
use App\Trust;
use App\Site;
use App\User;
use App\Role;
use App\Permission;
use App\Module;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		DomainController::truncate();
		Trust::truncate();
		Site::truncate();
		Role::truncate();
		Permission::truncate();
		Module::truncate();

		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

		Model::unguard();
		$this->call('DomainControllersTableSeeder');
		$this->call('TrustsTableSeeder');
		$this->call('SitesTableSeeder');
		$this->call('RolesTableSeeder');
		$this->call('ModulesTableSeeder');
		$this->call('PermissionsTableSeeder');
	}

}
