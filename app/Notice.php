<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model {

	public function resources()
	{
		return $this->morphToMany('App\Resource', 'resourceable');
	}

	public function comments() {
		return $this->hasMany('App\Comment');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
