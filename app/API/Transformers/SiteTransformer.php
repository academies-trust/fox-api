<?php 
namespace App\API\Transformers;

use App\Site;
use League\Fractal\TransformerAbstract;

class SiteTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'users',
		'trust',
		'groups',
		'modules',
		'behaviourModels',
		'markingSchemes',
		'siteUsers',
	];

	public function transform(Site $site)
	{
		$template = [
			'id'	=> $site['id'],
			'name' 	=> $site['name'],
			'slug' 	=> $site['slug'],
			'color' => $site['hex_color'],
			'type' => $site['type'],
			'created' => $site['created_at'],
			'links' => [
				'rel' => 'self',
				'uri' => '/sites/'.$site['id']
			]
		];
		return $template;
	}

	public function includeUsers(Site $site)
	{
		$users = $site->users;
		return $this->collection($users, new UserTransformer);
	}
	public function includeTrust(Site $site)
	{
		$trust = $site->trust;
		return $this->item($trust, new TrustTransformer);
	}
	public function includeGroups(Site $site)
	{
		$groups = $site->groups;
		return $this->collection($groups, new GroupTransformer);
	}
	public function includeModules(Site $site)
	{
		$modules = $site->modules;
		return $this->collection($modules, new ModuleTransformer);
	}
	public function includeBehaviourModels(Site $site)
	{
		$behaviourModels = $site->behaviourModels;
		return $this->collection($behaviourModels, new BehaviourModelTransformer);
	}
	public function includeMarkingSchemes(Site $site)
	{
		$markingSchemes = $site->markingSchemes;
		return $this->collection($markingSchemes, new MarkingSchemeTransformer);
	}
	public function includeSiteUsers(Site $site)
	{
		$siteUser = $site->siteUser;
		return $this->collection($siteUser, new SiteUserTransformer);
	}
}