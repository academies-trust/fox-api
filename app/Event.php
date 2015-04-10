<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	public function posts()
	{
		return $this->morphMany('App\Post', 'postable');
	}

	public function eventable()
	{
		return $this->morphTo();
	}

	public function comments() {
		return $this->hasMany('App\Comment');
	}

}
