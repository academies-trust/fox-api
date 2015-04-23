<?php 
namespace App\API\Transformers;

use App\SiteUser;
use League\Fractal\TransformerAbstract;

class SiteUserTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'site',
		'user',
		'role',
	];

	public function transform(SiteUser $siteUser)
	{
		$template = [
			'links' => [
				'rel' => 'self',
				'uri' => '/siteUsers/'.$siteUser['id']
			]
		];
		return $template;
	}

	public function includeSite(SiteUser $siteUser)
	{
		$site = $siteUser->site;
		return $this->item($site, new SiteTransformer);
	}

	public function includeUser(SiteUser $siteUser)
	{
		$user = $siteUser->user;
		return $this->item($user, new UserTransformer);
	}

	public function includeRole(SiteUser $siteUser)
	{
		$role = $siteUser->role;
		return $this->item($role, new RoleTransformer);
	}
}