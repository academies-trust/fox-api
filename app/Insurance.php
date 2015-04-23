<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model {

	protected $dates = ['valid_at', 'expires_at'];

	public function scheme() {
		$this->hasOne('App\Scheme');
	}

}
