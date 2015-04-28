<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {

	protected $fillable = ['name', 'slug', 'domain_controller_id', 'hex_color', 'type', 'trust_id'];

	public function domainController()
	{
		return $this->belongsTo('App\DomainController');
	}

	public function modules() {
		return $this->hasMany('App\Module');
	}

	public function users() {
		return $this->hasMany('App\User');
	}

	public function siteUser() {
		return $this->hasMany('App\SiteUser');
	}

	public function behaviourModels()
	{
		return $this->hasMany('App\BehaviourModel');
	}

}
