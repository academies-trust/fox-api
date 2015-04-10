<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model {

	public function user() {
		$this->belongsTo('App\User');
	}

	public function device() {
		$this->belongsTo('App\Device');
	}

	public function status() {
		$this->belongsTo('App\ClaimStatus');
	}
	
	public function updates() {
		$this->hasMany('App\ClaimUpdate');
	}

}
