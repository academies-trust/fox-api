<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

	use SoftDeletes;

	public function postable()
	{
		return $this->morphTo();
	}

	public function user() {
		return $this->belongsTo('App\User');
	}

}
