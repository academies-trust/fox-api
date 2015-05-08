<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model {

	public function resources()
	{
		return $this->morphToMany('App\Resource', 'resourceable');
	}

	public function student() {
		return $this->belongsTo('App\User', 'student_id');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function group()
	{
		return $this->belongsTo('App\Group');
	}

	public function comments() {
		return $this->morphMany('App\Comment', 'commentable');
	}

}
