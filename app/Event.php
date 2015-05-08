<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	protected 	$dates = ['starts_at', 'ends_at'],
				$fillable = ['starts_at', 'ends_at', 'group_id', 'allow_comments'];

	public function resources()
	{
		return $this->morphToMany('App\Resource', 'resourceable');
	}

	public function lessons()
	{
		return $this->hasOne('App\Lesson');
	}

	public function comments() {
		return $this->hasMany('App\Comment');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
