<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceAlert extends Model {

	protected $dates = ['expires_at'];

	public function resources()
	{
		return $this->morphToMany('App\Resource', 'resourceable');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
