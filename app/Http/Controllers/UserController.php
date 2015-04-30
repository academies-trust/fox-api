<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use adLDAP;
use App\User;
use JWTAuth;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\CursorInterface;
use Validator;
use Crypt;

class UserController extends ApiController {

	private $options = [];

	public function sync() {
		$domainControllers = \App\DomainController::all();
		foreach($domainControllers as $domainController) {
			$this->options = [
				'account_suffix' => $domainController->account_suffix,
				'base_dn' => $domainController->base_dn,
				'domain_controllers' => [$domainController->domain_controller],
				'admin_username' => $domainController->admin_username,
				'admin_password' => Crypt::decrypt($domainController->admin_password),
				'use_ssl' => (bool) $domainController->use_ssl,
				'ad_port' => $domainController->ad_port,
			];

			$this->adLDAP = new adLDAP($this->options);

			$this->getUsers($domainController);
		}
	}

	protected function getUsers($domainController) {
		$alphas = range('A','Z');
		foreach($alphas as $alpha) {
			$this->getUsersByAlpha($domainController, $alpha);
		}
	}

	protected function getUsersByAlpha($domainController, $alpha) {
		$users = $this->adLDAP->user()->all(false, $alpha.'*');
		foreach($users as $user) {
			if(stripos($user, $alpha) === 0) {
				$this->syncUser($domainController, $user);
			}
		}
	}

	protected function syncUser($domainController, $user)
	{
		if($this->adLDAP->user()->inGroup($user, 'Fox_Student') || $this->adLDAP->user()->inGroup($user, 'Fox_Staff')) {
			if($this->isDisabled($user))
			{
				$userinfo = $this->getADInfo($user);
				if(isset($userinfo[0]['mail'][0])) {
					$user = User::where('email', $userinfo[0]['mail'][0])->first();
					if($user)
					{
						$this->deleteUser($user);
					}
				}
			} else {
				$sites = $domainController->sites()->get();
				foreach($sites as $site)
				{
					if($this->adLDAP->user()->inGroup($user, 'Fox_'.strtoupper($site->slug)))
					{
						$rUser = $this->createADUser($user, $site);
						if($rUser) {
							$this->syncUserSites($rUser);
						}
						break 1;
					}
				}
			}
		}
	}

	protected function getADInfo($user)
	{
		return $this->adLDAP->user()->info($user);
	}

	protected function isDisabled($user)
	{
		$user_info=	$this->adLDAP->user()->info($user,array("useraccountcontrol"));
		return (($user_info[0]['useraccountcontrol'][0] & 2) == 1);
	}

	protected function createADUser($user, $site)
	{
		$userinfo = $this->getADInfo($user);
		if(isset($userinfo[0]['mail'][0])) {
			$email  	= strtolower($userinfo[0]['mail'][0]);
			$currentUser = User::where('email', $email)->get();
			if($currentUser->count() === 0) {
				
	        	$display 	= $userinfo[0]['displayname'][0];
	        	$auth_site 	= $site->id;
	    		$currentUser = User::create([
	    			'name' => $display,
	    			'email' => $email,
	    			'username' => $user,
	    			'auth_site_id' => $auth_site,
	    			'password' => bcrypt('temporary'),
	    		]);
	    		if($currentUser) {
	    			return $currentUser;
	    		} else {
	    			// TBD Log error - unable to create user
	    		}
	    	} else {
	    		return $currentUser->first();
	    	}
		} else {
			// TBD Log error - AD Account doesn't have email address
		}
	}

	protected function syncUserSites(User $user)
	{
		if($this->adLDAP->user()->inGroup($user->username, 'Fox_Staff')) {
    		$role = 1;
    	}
    	if($this->adLDAP->user()->inGroup($user->username, 'Fox_Student')) {
    		$role = 2;
    	}
    	if($role)
    	{
    		$sites = $user->sites()->get()->lists('id');
	    	$adSites = [];
	    	foreach(\App\Site::all() as $uSite)
	    	{
	    		if($this->adLDAP->user()->inGroup($user->username, 'Fox_'.strtoupper($uSite->slug))) {
		    		array_push($adSites, $uSite->id);
		    	}	
	    	}
	    	foreach($adSites as $adSite)
	    	{
	    		if(!in_array($adSite, $sites)) {
	    			$this->addSiteUser($user->id, $adSite, $role);
	    		}
	    	}
	    	foreach($sites as $uSite) {
	    		if(!in_array($uSite, $adSites)) {
	    			$this->removeSiteUser($user->id, $adSite, $role);
	    		}
	    	}
    	}
	}

	public function addSiteUser($user_id, $site_id, $role_id) {
		$siteUser = \App\SiteUser::create([
			'user_id' => $user_id,
			'site_id' => $site_id,
			'role_id' => $role_id,
		]);
		$this->addDefaultGroups($user_id, $site_id, $role_id);
	}

	public function removeSiteUser($user_id, $site_id, $role_id = null) {
		if($role_id)
		{
			$this->removeSiteUserByRole($user_id, $site_id, $role_id);
		} else {
			$siteUsers = \App\SiteUser::where('user_id', $user_id)->where('site_id', $site_id)->delete();
		}
	}

	public function removeSiteUserByRole($user_id, $site_id, $role_id)
	{
		$siteUser = \App\SiteUser::where('user_id', $user_id)->where('site_id', $site_id)->where('role_id', $role_id)->delete();
		$remainingSU = \App\SiteUser::where('user_id', $user_id)->where('site_id', $site_id)->count();
		if($remainingSU === 0)
		{
			$this->removeUserGroupsBySite($user_id, $site_id);
		}
	}

	public function addDefaultGroups($user_id, $site_id, $role_id)
	{
		$groups = \App\Site::find($site_id)->defaultGroups()->get()->lists('id');
		switch ($role_id) {
			case '1':
				$permission_id = 3;
				break;
			
			case '2':
				$permission_id = 4;
				break;
		}
		$user = User::find($user_id);

		foreach($groups as $group)
		{
			$user->groups()->attach($group, ['permission_id' => $permission_id]);
		}
	}

	protected function removeUserGroupsBySite($user_id, $site_id)
	{
		$groups = \App\User::find($user_id)->groups()->whereHas('sites', function($q) use ($site_id)
		{
			$q->where('id', $site_id);
		})->get()->lists('id');

		User::find($user_id)->groups()->detach($groups);
	}

	protected function deleteUser(User $user)
	{
		foreach($user->sites()->get() as $site)
		{
			$this->removeUserGroupsBySite($user->id, $site->id);
			$this->removeSiteUser($user->id, $site->id);
		}
		$user->delete();
	}

	public function signin(Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			[
				'username' => 'required',
				'password' => 'required'
			]
		);
		if($validator->fails()) {
			return $this->errorValidation('Please make sure you include both the username/email and password');
		}
		$site = ($request->site)? $request->site : null;
		$user = $this->findUser($request->username, $site);
		if($user)
		{
	    	return $this->authenticate($user, $request);
	    }
	}

	public function findUser($username, $site = null) {

		if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
	        $user = User::where('email', $username);
	    } else {
	    	if($site) {
	    		$user = User::where('username', $username)->where('site', $site);
	    	} else {
	    		$user = User::where('username', $username);
	    	}
	    }
	    if($user->count() == 0)
	    {
	    	return $this->errorNotFound('User not found');
	    } else if($user->count() > 1) {
	    	return $this->errorConflict('Multiple users with this username. Please try login with site field containing a site_id');
	    }

	    return $user->first();
	}

	public function getAssociatedSites($username) {
		return User::where('username', $username)->lists('auth_site_id');
	}

	public function authenticate(User $user, Request $request)
	{
		
		$sites = $user->SiteUser()->where('site_id', $user->auth_site_id)->get();
		foreach($sites as $site) {
			if(in_array($site->role->name, ['student', 'staff']))
			{
				$this->ADAuthenticate($user, $request);
			}
		}

    	// grab credentials from the request
        $credentials = ['email' => $user->email, 'password' => $request->password];
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->errorUnauthorised('Invalid Credentials');
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->errorInternalError('Unable to create token');
        }
        // all good so return the token
        $data = array('data' => ['token' => $token]);
		return $this->respondWithArray($data);
	}

	private function ADAuthenticate(User $user, Request $request)
	{
		$dc = $user->authSite->domainController;

		$this->options = [
				'account_suffix' => $dc->account_suffix,
				'base_dn' => $dc->base_dn,
				'domain_controllers' => [$dc->domain_controller],
				'admin_username' => $dc->admin_username,
				'admin_password' => Crypt::decrypt($dc->admin_password),
				'use_ssl' => (bool) $dc->use_ssl,
				'ad_port' => $dc->ad_port,
			];

		$this->adLDAP = new adLDAP($this->options);

		if($this->adLDAP->authenticate($user->username, $request->password))
		{
			$user->password = bcrypt($request->password);
			$user->save();
			$this->syncSites($this->adLDAP, $user->username);
		} else {
			return 'failed auth';
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
