<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model {

	public function resourceable()
	{
		return $this->morphTo();
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
