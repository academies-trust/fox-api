<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ClaimUpdate extends Model {

	use SoftDeletes;

    protected $dates = ['deleted_at'];

	public function user() {
		$this->belongsTo('App\Claim');
	}

	public function status() {
		$this->belongsTo('App\ClaimStatus');
	}

}
