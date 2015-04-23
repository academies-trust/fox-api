<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceAlert extends Model {

	protected $dates = ['expires_at'];

	public function posts()
	{
		return $this->morphMany('App\Post', 'postable');
	}

}
