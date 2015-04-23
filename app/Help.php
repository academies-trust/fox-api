<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Help extends Model {

	public function posts()
	{
		return $this->morphMany('App\Post', 'postable');
	}

	public function group() {
		return $this->belongsTo('App\Group');
	}

	public function content()
	{
		return $this->hasMany('content');
	}

	public function activeContent()
	{
		return $this->hasMany('content')->where('active', '1');
	}

}
