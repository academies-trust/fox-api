<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model {

	public function events()
	{
		return $this->belongsTo('App\Event');
	}

	public function class() {
		return $this->belongsTo('App\Class');
	}

	public function comments() {
		return $this->hasMany('App\Comment');
	}

}
