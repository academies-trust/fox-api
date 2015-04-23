<?php 
namespace App\API\Transformers;

use App\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'type',
	];
	protected $availableIncludes = [
		'user',
	];

	public function transform(Post $post)
	{
		$template = [
			'id'	=> (int) $post['id'],
			'created'	=> $post['created_at'],
			'published'	=> $post['published_at'],
			'updated'	=> $post['updated_at'],
			'deleted'	=> $post['deleted_at'],
			'links' => [
				'rel' => 'self',
				'uri' => '/posts/'.$post['id']
			]
		];
		return $template;
	}

	public function includeUser(Post $post)
	{
		$user = $post->user;
		return $this->item($user, new UserTransformer);
	}

	public function includeType(Post $post)
	{
		$type = $post->postable;
		switch ($post->postable_type) {
			case 'ServiceAlert':
				return $this->item($item, new ServiceAlertTransformer);
				break;
			case 'Resource':
				return $this->item($item, new ResourceTransformer);
				break;
			case 'Event':
				return $this->item($item, new EventTransformer);
				break;
			case 'Notice':
				return $this->item($item, new NoticeTransformer);
				break;
			case 'Article':
				return $this->item($item, new ArticleTransformer);
				break;
			case 'Submission':
				return $this->item($item, new SubmissionTransformer);
				break;
			case 'Task':
				return $this->item($item, new TaskTransformer);
				break;
			case 'Notification':
				return $this->item($item, new NotificationTransformer);
				break;
			case 'Feedback':
				return $this->item($item, new FeedbackTransformer);
				break;
			case 'Help':
				return $this->item($item, new HelpTransformer);
				break;
			case 'Comment':
				return $this->item($item, new CommentTransformer);
				break;
			case 'Behaviour':
				return $this->item($item, new BehaviourTransformer);
				break;
		}
	}
}