<?php 
namespace App\API\Transformers;

use App\Notification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'posts'
	];

	public function transform(Notification $notification)
	{
		$template = [
			'id'	=> (int) $notification['id'],
			'message' => $notification['message'],
			'links' => [
				'rel' => 'self',
				'uri' => '/notifications/'.$notification['id']
			]
		];
		return $template;
	}

	public function includePosts(Notification $notification)
	{
		$posts = $notification->readable;
		return $this->collection($posts, new PostTransformer);
	}
}