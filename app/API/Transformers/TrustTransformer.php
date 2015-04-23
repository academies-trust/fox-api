<?php 
namespace App\API\Transformers;

use App\Trust;
use League\Fractal\TransformerAbstract;

class TrustTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'sites',
	];

	public function transform(Trust $Trust)
	{
		$template = [
			'id'	=> $trust['id'],
			'name' 	=> $trust['name'],
			'links' => [
				'rel' => 'self',
				'uri' => '/trusts/'.$trust['id']
			]
		];
		return $template;
	}

	public function includeSites(Trust $trust)
	{
		$sites = $trust->sites;
		return $this->collection($sites, new SiteTransformer);
	}
}