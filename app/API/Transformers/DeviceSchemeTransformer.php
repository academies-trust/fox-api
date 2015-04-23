<?php 
namespace App\API\Transformers;

use App\DeviceScheme;
use League\Fractal\TransformerAbstract;

class DeviceSchemeTransformer extends TransformerAbstract {

	protected $optionalIncludes = [
		'post'
	];

	public function transform(DeviceScheme $deviceScheme)
	{
		$template = [
			'id'	=> (int) $deviceScheme['id'],
			'name' => $deviceScheme['message'],
			'site' => $deviceScheme['site'],
			'links' => [
				'rel' => 'self',
				'uri' => '/deviceSchemes/'.$deviceScheme['id']
			]
		];
		return $template;
	}

	public function includePost(DeviceScheme $deviceScheme)
	{
		$post = $deviceScheme->post;
		return $this->item($post, new PostTransformer);
	}
}