<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	use SoftDeletes;

    protected $dates = ['deleted_at'];

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
	protected $fillable = ['name', 'email', 'username', 'password', 'auth_site_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function notifications() {
		return $this->hasMany('App\Notification');
	}

	public function authSite()
	{
		return $this->belongsTo('App\Site');
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

	public function groupUser()
	{
		return $this->hasMany('App\GroupUser');
	}
	public function permissions()
	{
		return $this->belongsToMany('App\Permission', 'group_user');
	}
	public function isStaff($site)
	{
		return $this->checkIfRole($site, 'staff');
	}
	public function isStudent($site)
	{
		return $this->checkIfRole($site, 'student');
	}
	public function checkIfRole($site, $role)
	{
		if(!is_int($site)) {
			$site = App\Site::where('slug',$site)->first()->id;
		}
		return $this->siteUser()->whereHas('role', function($q)
		{
			$q->where('name', $role);
		})->count();
	}

}
