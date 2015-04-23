<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class HelpContent extends Model {

	public function parent() {
		return $this->belongsTo('App\Help');
	}

}
