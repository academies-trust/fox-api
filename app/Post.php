<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

	protected 	$fillable = ['published_at', 'readable_id', 'readable_type', 'created_at', 'updated_at', 'read_at', 'user_id'];
	protected 	$dates = ['read_at', 'published_at'];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function readable()
	{
		return $this->morphTo();
	}

}
