<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\SiteUser;
use Faker\Factory as Faker;
class SiteUserTableSeeder extends Seeder {
	public function run()
	{
		$faker = Faker::create('en_GB');
		foreach(range(1,1) as $index)
		{
			SiteUser::create([
				'site_id' => 1,
				'user_id' => 1,
				'role_id' => 1,
			]);
		}
	}
}