<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model {

	public function scheme() {
		$this->hasOne('App\Scheme');
	}

}
