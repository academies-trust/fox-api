<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteUser extends Model {

	protected $table = 'site_user';

	public function User() {
		$this->belongsTo('App\User');
	}

	public function Site() {
		$this->belongsTo('App\Site');
	}

	public function Role() {
		$this->belongsTo('App\Site');
	}

}
