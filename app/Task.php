<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

	public function posts()
	{
		return $this->morphMany('App\Post', 'postable');
	}

	public function group()
	{
		return $this->belongsTo('App\Group');
	}

}
