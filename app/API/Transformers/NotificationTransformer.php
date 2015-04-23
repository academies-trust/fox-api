<?php 
namespace App\API\Transformers;

use App\Notification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'post'
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

	public function includePost(Notification $notification)
	{
		$post = $notification->post;
		return $this->item($post, new PostTransformer);
	}
}