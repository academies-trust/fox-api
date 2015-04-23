<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;
use Faker\Factory as Faker;
class RolesTableSeeder extends Seeder {
	public function run()
	{
		$faker = Faker::create('en_GB');
		$roles = ['staff','student','parent','governor'];
		foreach(range(1,count($roles)) as $index)
		{
			Role::create([
				'name' => $roles[$index - 1]
			]);
		}
	}
}