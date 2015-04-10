<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {

	public function modules() {
		return $this->belongsToMany('App\Module', 'module_group');
	}

	public function posts() {
		return $this->hasMany('App\Post');
	}

	public function members() {
		return $this->belongsToMany('App\User');
	}

}
