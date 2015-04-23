<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Trust;
use Faker\Factory as Faker;

class TrustsTableSeeder extends Seeder {
	public function run()
	{
		$faker = Faker::create('en_GB');
		foreach(range(1,1) as $index)
		{
			Trust::create([
				'name' => 'The Hastings Academies Trust',
			]);
		}
	}
}