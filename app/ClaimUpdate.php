<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ClaimUpdate extends Model {

	public function user() {
		$this->belongsTo('App\Claim');
	}

	public function status() {
		$this->belongsTo('App\ClaimStatus');
	}

}
