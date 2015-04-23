<?php 
namespace App\API\Transformers;

use App\ServiceAlert;
use League\Fractal\TransformerAbstract;

class ServiceAlertTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'post'
	];

	protected $availableIncludes = [
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

	public function includePost(ServiceAlert $serviceAlert)
	{
		$post = $serviceAlert->post;
		return $this->item($post, new PostTransformer);
	}
}