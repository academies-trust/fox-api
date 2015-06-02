<?php 
namespace App\API\Transformers;

use App\Notice;
use League\Fractal\TransformerAbstract;

class NoticeTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'group',
		'comments',
		'posts'
	];

	public function transform(Notice $notice)
	{
		$template = [
			'id'	=> (int) $notice['id'],
			'start' => $notice['starts_at'],
			'end' => $notice['ends_at'],
			'comments_enabled' => (bool) $notice['allow_comments'],
			'links' => [
				'rel' => 'self',
				'uri' => '/notices/'.$notice['id']
			]
		];
		return $template;
	}

	public function includeGroup(Notice $notice)
	{
		$group = $notice->group;
		return $this->item($group, new GroupTransformer);
	}
	public function includeComments(Notice $notice)
	{
		$comments = $notice->comments;
		return $this->collection($comments, new CommentTransformer);
	}
	
	public function includePosts(Notice $notice)
	{
		$posts = $notice->readable;
		return $this->collection($posts, new PostTransformer);
	}
}