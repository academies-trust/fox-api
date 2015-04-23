<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model {

	protected $dates = ['read_at', 'submiutted_at', 'marked_at'];

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
