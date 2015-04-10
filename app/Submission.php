<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model {

	public function posts()
	{
		return $this->morphMany('App\Post', 'postable');
	}

	public function user() {
		return $this->belongsTo('App\User');
	}
	
	public function resources() {
		return $this->hasMany('App\Resource');
	}

	public function comments() {
		return $this->hasMany('App\Comment', 'parent_id');
	}

}
