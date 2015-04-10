<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Class extends Model {

	public function group() {
		return $this->belongsTo('App\Group');
	}

	public function lessons() {
		return $this->hasMany('App\Lesson');
	}

}
