<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model {

	protected $table = 'group_user';

	public function group()
	{
		return $this->belongsTo('App\Group');
	}
	public function permission()
	{
		return $this->belongsTo('App\Permission');
	}
	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
