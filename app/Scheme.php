<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Scheme extends Model {

	public function devices() {
		$this->hasMany('App\Device');
	}

}
