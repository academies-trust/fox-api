<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class MarkingScheme extends Model {

	use SoftDeletes;

	public function author() {
		return $this->belongsTo('App\User');
	}

	public function grades() {
		return $this->hasMany('App\Grading');
	}

}
