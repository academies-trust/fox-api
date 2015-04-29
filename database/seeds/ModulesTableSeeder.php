<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Module;
use Faker\Factory as Faker;
class ModulesTableSeeder extends Seeder {
	public function run()
	{
		$faker = Faker::create('en_GB');
		$modules = [
			'articles',
			'events',
			'notices',
			'resources',
			'tasks'
		];
		foreach(range(1,count($modules)) as $index)
		{
			Module::create([
				'name' => $modules[$index - 1],
			]);
		}
	}
}