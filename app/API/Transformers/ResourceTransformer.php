<?php 
namespace App\API\Transformers;

use App\Resource;
use League\Fractal\TransformerAbstract;

class ResourceTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'groups',
		'posts',
	];

	public function transform(Resource $resource)
	{
		$template = [
			'id' => $resource['id'],
			'filename' => $resouce['filename'],
			'filesize' => $resource['filesize'],
			'links' => [
				'rel' => 'self',
				'uri' => '/resources/'.$resource['id']
			]
		];
		return $template;
	}

	public function includeGroups(Resource $resource)
	{
		$groups = $resource->groups;
		return $this->collection($groups, new GroupTransformer);
	}
	public function includePosts(Resource $resource)
	{
		$posts = $resource->posts;
		return $this->collection($posts, new PostTransformer);
	}
}