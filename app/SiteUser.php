<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteUser extends Model {

	protected $table = 'site_user';

	protected $fillable = ['site_id', 'user_id', 'role_id'];


	public $timestamps = false;

	public function user() {
		return $this->belongsTo('App\User');
	}

	public function site() {
		return $this->belongsTo('App\Site');
	}

	public function role() {
		return $this->belongsTo('App\Role');
	}

}
