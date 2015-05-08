<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

	protected $dates = ['due_at'];

	public function resources()
	{
		return $this->morphToMany('App\Resource', 'resourceable');
	}

	public function group()
	{
		return $this->belongsTo('App\Group');
	}

	public function submissions() {
		return $this->hasMany('App\Notification');
	}

}
