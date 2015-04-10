<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Grading extends Model {

	public function scheme() {
		return $this->belongsTo('App\MarkingScheme');
	}

}
