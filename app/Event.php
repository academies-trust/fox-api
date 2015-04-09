<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	public function events()
	{
		return $this->morphMany('App\Post', 'postable');
	}

	public function eventable()
	{
		return $this->morphTo();
	}

}
