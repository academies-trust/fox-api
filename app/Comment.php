<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	public function posts()
	{
		return $this->morphMany('App\Post', 'postable');
	}

	public function parent()
	{
		return $this->belongsTo('App\Post', 'parent_id');
	}

}
