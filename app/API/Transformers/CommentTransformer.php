<?php 
namespace App\API\Transformers;

use App\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'post'
	];

	protected $availableIncludes = [
		'parent',
	];

	public function transform(Comment $comment)
	{
		$template = [
			'id'	=> (int) $comment['id'],
			'content' => $comment['content'],
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
	public function includePost(Comment $comment)
	{
		$post = $comment->post;
		return $this->item($post, new PostTransformer);
	}
}