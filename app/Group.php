<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model {

	use SoftDeletes;

    protected $dates = ['deleted_at'];

	public function modules() {
		return $this->belongsToMany('App\Module', 'module_group');
	}

	public function events()
	{
		return $this->hasMany('App\Event');
	}

	public function posts() {
		return $this->hasMany('App\Post');
	}

	public function users() {
		return $this->belongsToMany('App\User');
	}

	public function groupUser()
	{
		return $this->hasMany('App\GroupUser');
	}

	public function sites()
	{
		return $this->belongsToMany('App\Site');
	}

}
