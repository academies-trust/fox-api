<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Faker\Factory as Faker;
class UsersTableSeeder extends Seeder {
	public function run()
	{
		$faker = Faker::create('en_GB');
		foreach(range(1,1) as $index)
		{
			User::create([
				'name' => 'Ralph Lawrence',
				'email' => 'r.lawrence@hasla.org.uk',
				'username' => 'r.lawrence',
				'password' => bcrypt('bologne'),
				'auth_site_id' => 1
			]);
		}
	}
}