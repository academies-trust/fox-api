<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	protected 	$dates = ['starts_at', 'ends_at'],
				$fillable = ['starts_at', 'ends_at', 'group_id', 'allow_comments'];
	public 		$timestamps = false;

	public function post()
	{
		return $this->morphOne('App\Post', 'postable');
	}

	public function eventable()
	{
		return $this->morphTo();
	}

	public function comments() {
		return $this->hasMany('App\Comment');
	}

}
