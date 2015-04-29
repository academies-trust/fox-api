<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Permission;
use Faker\Factory as Faker;
class PermissionsTableSeeder extends Seeder {
	public function run()
	{
		$faker = Faker::create('en_GB');
		$permissions = [
			['Owner', true, true, true, true, true],
			['Administrator', true, true, true, true, false],
			['Editor', true, true, true, false, false],
			['Contributor', true, true, false, false, false],
			['Reader', true, false, false, false, false],
		];
		foreach(range(1,count($permissions)) as $index)
		{
			Permission::create([
				'name' => $permissions[$index - 1][0],
				'read' => (bool) $permissions[$index - 1][1],
				'contribute' => (bool) $permissions[$index - 1][2],
				'write' => (bool) $permissions[$index - 1][3],
				'admin' => (bool) $permissions[$index - 1][4],
				'own' => (bool) $permissions[$index - 1][5]
			]);
		}
	}
}