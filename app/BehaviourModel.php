<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BehaviourModel extends Model {

	use SoftDeletes;

    protected $dates = ['deleted_at'];

	public function behaviour()
	{
		return $this->hasMany('App\Behaviour');
	}

	public function site()
	{
		return $this->belongsTo('App\Site');
	}

}