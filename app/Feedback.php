<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model {

	public function posts()
	{
		return $this->morphMany('App\Post', 'postable');
	}

	public function student() {
		return $this->belongsTo('App\User', 'student_id');
	}

	public function group()
	{
		return $this->belongsTo('App\Group');
	}

}
