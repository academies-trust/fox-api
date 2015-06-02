<?php 
namespace App\API\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract;

class EventTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'post'
	];

	protected $availableIncludes = [
		'group',
		'lesson',
		'comments'
	];

	public function transform(Event $event)
	{
		$template = [
			'id'	=> (int) $event['id'],
			'start' => $event['starts_at'],
			'end' => $event['ends_at'],
			'comments_enabled' => (bool) $event['allow_comments'],
			'links' => [
				'rel' => 'self',
				'uri' => '/events/'.$event['id']
			]
		];
		return $template;
	}

	public function includeGroup(Event $event)
	{
		$group = $event->group;
		return $this->item($group, new GroupTransformer);
	}
	public function includeLesson(Event $event)
	{
		$lesson = $event->lesson;
		return $this->item($lesson, new LessonTransformer);
	}
	public function includeComments(Event $event)
	{
		$comments = $event->comments;
		return $this->collection($comments, new CommentTransformer);
	}
	public function includePost(Event $event)
	{
		$post = $event->post;
		return $this->item($post, new PostTransformer);
	}

	public function includePosts(Event $event)
	{
		$posts = $event->readable;
		return $this->collection($posts, new PostTransformer);
	}
}