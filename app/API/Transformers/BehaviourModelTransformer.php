<?php 
namespace App\API\Transformers;

use App\BehaviourModel;
use League\Fractal\TransformerAbstract;

class BehaviourModelTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'behaviours',
		'site',
	];

	public function transform(BehaviourModel $behaviourModel)
	{
		$template = [
			'id'	=> (int) $behaviourModel['id'],
			'name' 	=> $behaviourModel['name'],
			'email' 	=> $behaviourModel['email'],
			'links' => [
				'rel' => 'self',
				'uri' => '/behaviourModels/'.$behaviourModel['id']
			]
		];
		return $template;
	}

	public function includeBehaviours(BehaviourModel $behaviourModel)
	{
		$behaviours = $behaviourModel->behaviours;
		return $this->collection($behaviours, new BehaviourTransformer);
	}
	public function includeSite(BehaviourModel $behaviourModel)
	{
		$site = $behaviourModel->site;
		return $this->item($site, new SiteTransformer);
	}
}