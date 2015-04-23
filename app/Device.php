<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model {

	use SoftDeletes;

    protected $dates = ['deleted_at'];

	public function user() {
		$this->belongsTo('App\User');
	}

	public function scheme() {
		$this->belongsTo('App\DeviceScheme');
	}

	public function claims() {
		$this->belongsTo('App\Claim');
	}

}
