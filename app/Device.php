<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model {

	public function user() {
		$this->belongsTo('App\User');
	}

	public function scheme() {
		$this->belongsTo('App\Scheme');
	}

	public function claims() {
		$this->belongsTo('App\Claim');
	}

}
