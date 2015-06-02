<?php 
namespace App\API\Transformers;

use App\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'parent',
		'user',
	];

	public function transform(Comment $comment)
	{
		$template = [
			'id'	=> (int) $comment['id'],
			'content' => $comment['content'],
			'created' => $comment['created_at'],
			'updated' => $comment['updated_at'],
			'published' => $comment['published_at'],
			'links' => [
				'rel' => 'self',
				'uri' => '/comments/'.$comment['id']
			]
		];
		return $template;
	}

	public function includeParent(Comment $comment)
	{
		$parent = $comment->parent;
		return $this->item($parent, new PostTransformer);
	}
	public function includeUser(Comment $comment)
	{
		$user = $comment->user;
		return $this->item($user, new UserTransformer);
	}

	public function includePosts(Comment $comment)
	{
		$posts = $comment->readable;
		return $this->collection($posts, new PostTransformer);
	}
}