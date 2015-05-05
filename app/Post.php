<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model {

	use SoftDeletes;

    protected 	$dates = ['deleted_at', 'published_at'],
    			$fillable = ['user_id', 'published_at', 'deleted_at', 'postable_id', 'postable_type'];

	public function postable()
	{
		return $this->morphTo();
	}

	public function event()
	{
		return $this->where('postable_type','App\Event')->morphTo();
	}

	public function lesson()
	{
		return $this->where('postable_type','App\Lesson')->morphTo();
	}

	public function article()
	{
		return $this->where('postable_type','App\Article')->morphTo();
	}

	public function comment()
	{
		return $this->where('postable_type','App\Comment')->morphTo();
	}

	public function help()
	{
		return $this->where('postable_type','App\Help')->morphTo();
	}

	public function notice()
	{
		return $this->where('postable_type','App\Notice')->morphTo();
	}

	public function notification()
	{
		return $this->where('postable_type','App\Notification')->morphTo();
	}

	public function resource()
	{
		return $this->where('postable_type','App\Resource')->morphTo();
	}

	public function serviceAlert()
	{
		return $this->where('postable_type','App\ServiceAlert')->morphTo();
	}

	public function submission()
	{
		return $this->where('postable_type','App\Submission')->morphTo();
	}

	public function task()
	{
		return $this->where('postable_type','App\Task')->morphTo();
	}

	public function user() {
		return $this->belongsTo('App\User');
	}

}
