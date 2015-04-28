<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use adLDAP;
use App\User;
use JWTAuth;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\CursorInterface;

use Illuminate\Http\Request;
use Validator;
use Crypt;

class UserController extends ApiController {

	private $options = [];

	public function __construct(Manager $fractal)
	{
		$this->fractal = $fractal;
		if (isset($_GET['include'])) {
		    $this->fractal->parseIncludes($_GET['include']);
		}
		if($this->middleware('checkToken', ['except' => ['signin', 'signup']])) {
			$this->user = $this->getAuthenticatedUser();
		}
	}

	public function sync() {
		$sites = \App\Site::all();
		foreach($sites as $site) {
			$this->getUsers($site);
			echo $site->name;
		}
	}

	protected function getUsers($site) {
		$alphas = range('A','Z');
		foreach($alphas as $alpha) {
			$this->getUsersByAlpha($site, $alpha);
		}
		echo ' - '.$alpha;
	}

	protected function getUsersByAlpha($site, $alpha) {
		$dc = $site->domainController;
		$this->options = [
				'account_suffix' => $dc->account_suffix,
				'base_dn' => $dc->base_dn,
				'domain_controllers' => [$dc->domain_controller],
				'admin_username' => $dc->admin_username,
				'admin_password' => Crypt::decrypt($dc->admin_password),
				'use_ssl' => (bool) $dc->use_ssl,
				'ad_port' => $dc->ad_port,
			];

		$adLDAP = new adLDAP($this->options);

		$users = $adLDAP->user()->all(false, $alpha.'*');
		foreach($users as $user) {
			if(stripos($user, $alpha) === 0) {
				if($adLDAP->user()->inGroup($user, 'Fox_Student') || $adLDAP->user()->inGroup($user, 'Fox_Staff')) {
					if($site->slug == 'tha' || $site->slug == 'tsla') {
						if($adLDAP->user()->inGroup($user, 'Fox_TSLA') && $site->slug == 'tsla') {
							$this->createADUser($adLDAP, $user, $site);
						}
						if($adLDAP->user()->inGroup($user, 'Fox_THA') && $site->slug == 'tha') {
							$this->createADUser($adLDAP, $user, $site);
						}
					} else {
						$this->createADUser($adLDAP, $user, $site);
					}
				}
			}
		}
	}

	protected function createADUser($adLDAP, $user, $site)
	{
		$userinfo = $adLDAP->user()->info($user);
		if(isset($userinfo[0]['mail'][0])) {
			$email  	= strtolower($userinfo[0]['mail'][0]);
			$currentUser = User::where('email', $email)->get();
			if($currentUser->count() === 0) {
				
	        	$display 	= $userinfo[0]['displayname'][0];
	        	$auth_site 	= $site->id;
	    		$newUser = User::create([
	    			'name' => $display,
	    			'email' => $email,
	    			'username' => $user,
	    			'auth_site_id' => $auth_site,
	    			'password' => bcrypt('temporary'),
	    		]);
	    		if($newUser) {
	    			if($adLDAP->user()->inGroup($user, 'Fox_Staff')) {
	    				$newUser->siteUser()->create([
	    					'site_id' => $auth_site,
	    					'user_id' => $newUser->id,
	    					'role_id' => 1
	    				]);
	    			}
	    			if($adLDAP->user()->inGroup($user, 'Fox_Student')) {
	    				$newUser->siteUser()->create([
	    					'site_id' => $auth_site,
	    					'user_id' => $newUser->id,
	    					'role_id' => 2
	    				]);
	    			}
	    		}
	    	}
		}
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

		$adLDAP = new adLDAP($this->options);

		if($adLDAP->authenticate($user->username, $request->password))
		{
			$user->password = bcrypt($request->password);
			$user->save();
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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
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
