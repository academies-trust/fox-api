<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model {

	public function events()
	{
		return $this->morphMany('App\Event', 'eventable');
	}

}
