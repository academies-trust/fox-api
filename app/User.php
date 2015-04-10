<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function notifications() {
		return $this->hasMany('App\Notification');
	}

	public function sites() {
		return $this->belongsToMany('App\Site');
	}

	public function siteUser() {
		return $this->hasMany('App\SiteUser');
	}

	public function groups() {
		return $this->belongsToMany('App\Group');
	}

	public function posts() {
		return $this->hasMany('App\Post');
	}

	public function articles() {
		return $this->hasMany('App\Article');
	}

	public function notices() {
		return $this->hasMany('App\Notice');
	}

	public function tasks() {
		return $this->hasMany('App\Task');
	}

	public function submissions()
	{
		return $this->hasMany('App\Submission', 'owner_id');
	}

	public function markingScheme() {
		return $this->hasMany('App\MarkingScheme');
	}

}
