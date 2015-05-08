<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

	protected 	$fillable = ['published_at', 'group_id', 'help', 'allow_comments'];
	protected 	$dates = ['deleted_at', 'published_at'];

	public function content()
	{
		return $this->hasMany('App\ArticleContent');
	}

	public function activeContent()
	{
		return $this->belongsTo('App\ArticleContent', 'content_id');
	}

	public function group() {
		return $this->belongsTo('App\Group');
	}

	public function comments() {
		return $this->hasMany('App\Comment', 'parent_id');
	}

	public function resources()
	{
		return $this->morphToMany('App\Resource', 'resourceable');
	}

}
