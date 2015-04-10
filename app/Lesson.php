<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model {

	public function events()
	{
		return $this->morphMany('App\Event', 'eventable');
	}

	public function class() {
		return $this->belongsTo('App\Class');
	}

	public function comments() {
		return $this->hasMany('App\Comment');
	}

}
