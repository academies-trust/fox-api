<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Article extends Model {

	protected 	$fillable = ['published_at', 'group_id', 'help', 'allow_comments', 'user_id'];
	protected 	$dates = ['deleted_at', 'published_at'];

	public function contents()
	{
		return $this->hasMany('App\ArticleContent');
	}

	public function scopeActive($query)
	{
		return $query->where('published_at', '<=', \Carbon\Carbon::now())->orderBy('published_at', 'desc')->where(function($q) {
			$q->whereNull('deleted_at')
				->orWhere('user_id', Auth::user()->id)
				->orWhereIn('group_id', Auth::user()->groupsWhereCan('admin')->lists('id'));
		})->orderBy('updated_at', 'desc');
	}

	public function activeContent()
	{
		return $this->belongsTo('App\ArticleContent', 'content_id');
	}

	public function group() {
		return $this->belongsTo('App\Group');
	}

	public function comments() {
		return $this->morphMany('App\Comment', 'commentable');
	}

	public function resources()
	{
		return $this->morphToMany('App\Resource', 'resourceable');
	}

}
