<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	protected $fillable = ['content', 'user_id'];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function resources()
	{
		return $this->morphToMany('App\Resource', 'resourceable');
	}

	public function commentable()
	{
		return $this->morphTo();
	}

}
