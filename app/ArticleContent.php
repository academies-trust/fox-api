<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleContent extends Model {

	use SoftDeletes;

	public 	$dates = ['approved_at'],
			$table = 'article_content';
	protected $fillable = ['title', 'content', 'approved_at', 'approved_by', 'user_id'];

	public function parent() {
		return $this->belongsTo('App\Article');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function approvedBy() {
		return $this->belongsTo('App\User', 'approved_by');
	}

}
