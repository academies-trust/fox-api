<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\DomainController;
use Faker\Factory as Faker;
class DomainControllersTableSeeder extends Seeder {
	public function run()
	{
		$faker = Faker::create('en_GB');
		$dcs = [
			['@hasla.org.uk', 'DC=hasla,DC=org,DC=uk', '8454612-DC01.hasla.org.uk', 'web.team', Crypt::encrypt('Hastings1'), 0, 389],
			['@elphin.lan', 'ou=staff,dc=elphin,dc=lan', 'ldap://217.177.12.2', 'techadmin', Crypt::encrypt('fishcake'), 0, 389],
		];
		foreach(range(1,count($dcs)) as $index)
		{
			DomainController::create([
				'account_suffix' => $dcs[$index - 1][0],
				'base_dn' => $dcs[$index - 1][1],
				'domain_controller' => $dcs[$index - 1][2],
				'admin_username' => $dcs[$index - 1][3],
				'admin_password' => $dcs[$index - 1][4],
				'use_ssl' => $dcs[$index - 1][5],
				'ad_port' => $dcs[$index - 1][6],
			]);
		}
	}
}