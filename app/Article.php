<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

	public function posts()
	{
		return $this->morphMany('App\Post', 'postable');
	}

}
