<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Site;
use Faker\Factory as Faker;
class SitesTableSeeder extends Seeder {
	public function run()
	{
		$faker = Faker::create('en_GB');
		$sites = [
			['The Hastings Academy', 'tha', 1, '0098c3', 'secondary', 1],
			['The St Leonards Academy', 'tsla', 1, '47068c', 'secondary', 1],
			//['The Baird Primary Academy', 'baird', 2, '4D4484', 'primary', 1],
		];
		foreach(range(1,count($sites)) as $index)
		{
			$site = Site::create([
				'name' => $sites[$index - 1][0],
				'slug' => $sites[$index - 1][1],
				'domain_controller_id' => $sites[$index - 1][2],
				'hex_color' => $sites[$index - 1][3],
				'type' => $sites[$index - 1][4],
				'trust_id' => $sites[$index - 1][5]
			]);

			$site->groups()->create([
				'name' => $sites[$index - 1][0],
				'open' => 1,
				'service_provider' => 0,
				'default' => 1,
			]);
		}
	}
}