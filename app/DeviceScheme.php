<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceScheme extends Model {

	use SoftDeletes;

    protected $dates = ['deleted_at'];

	public function devices() {
		$this->hasMany('App\Device');
	}

}
