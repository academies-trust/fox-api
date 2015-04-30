<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DomainController extends Model {

	public $timestamps = false;

	public function sites()
	{
		return $this->hasMany('App\Site');
	}

}
