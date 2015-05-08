<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model {

	protected $dates = ['read_at', 'submitted_at', 'marked_at'];

	public function user() {
		return $this->belongsTo('App\User');
	}
	
	public function resources()
	{
		return $this->morphToMany('App\Resource', 'resourceable');
	}

	public function comments() {
		return $this->hasMany('App\Comment', 'parent_id');
	}

}
