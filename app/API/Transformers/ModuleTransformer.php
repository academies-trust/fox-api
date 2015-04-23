<?php 
namespace App\API\Transformers;

use App\Module;
use League\Fractal\TransformerAbstract;

class ModuleTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'sites',
		'groups',
	];

	public function transform(Module $module)
	{
		$template = [
			'id'	=> $module['id'],
			'name' 	=> $module['name'],
			'links' => [
				'rel' => 'self',
				'uri' => '/modules/'.$module['id']
			]
		];
		return $template;
	}

	public function includeSites(Module $module)
	{
		$sites = $module->sites;
		return $this->collection($sites, new SiteTransformer);
	}
	public function includeGroups(Module $module)
	{
		$groups = $module->groups;
		return $this->collection($groups, new GroupTransformer);
	}
}