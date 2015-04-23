<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

	use SoftDeletes;

    protected $dates = ['deleted_at', 'published_at'];

	public function postable()
	{
		return $this->morphTo();
	}

	public function user() {
		return $this->belongsTo('App\User');
	}

}
