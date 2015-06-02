<?php 
namespace App\API\Transformers;

use App\ServiceAlert;
use League\Fractal\TransformerAbstract;

class ServiceAlertTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'posts'
	];

	public function transform(ServiceAlert $serviceAlert)
	{
		$template = [
			'expires' => $serviceAlert['expires_at'],
			'links' => [
				'rel' => 'self',
				'uri' => '/serviceAlerts/'.$serviceAlert['id']
			]
		];
		return $template;
	}

	public function includePosts(ServiceAlert $serviceAlert)
	{
		$posts = $serviceAlert->readable;
		return $this->collection($posts, new PostTransformer);
	}
}