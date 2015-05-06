<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

	public function posts()
	{
		return $this->morphMany('App\Post', 'postable');
	}

	public function content()
	{
		return $this->hasMany('App\ArticleContent');
	}

	public function group() {
		return $this->belongsTo('App\Group');
	}

	public function comments() {
		return $this->hasMany('App\Comment', 'parent_id');
	}

}
