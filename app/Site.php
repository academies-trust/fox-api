<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {

	public function modules() {
		return $this->hasMany('App\Module');
	}

	public function users() {
		return $this->hasMany('App\User');
	}

	public function siteUser() {
		return $this->hasMany('App\SiteUser');
	}

}
