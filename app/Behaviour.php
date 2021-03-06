<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Behaviour extends Model {

	public function posts()
	{
		return $this->morphMany('App\Post', 'postable');
	}

	public function student() {
		return $this->belongsTo('App\User', 'student_id');
	}

	public function model()
	{
		return $this->belongsTo('App\BehaviourModel');
	}

	public function resources()
	{
		return $this->morphToMany('App\Resource', 'resourceable');
	}

	public function comments() {
		return $this->morphMany('App\Comment', 'commentable');
	}

}