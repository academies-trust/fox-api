<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model {

	use SoftDeletes;

	public function classes()
	{
		return $this->hasMany('App\Class');
	}

	public function site()
	{
		return $this->belongsTo('App\Site');
	}

}
