<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

	public function resources()
	{
		return $this->morphToMany('App\Resource', 'resourceable');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
