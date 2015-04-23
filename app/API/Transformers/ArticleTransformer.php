<?php 
namespace App\API\Transformers;

use App\Article;
use League\Fractal\TransformerAbstract;

class ArticleTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'post'
	];

	protected $availableIncludes = [
		'group',
		'comments'
	];

	public function transform(Article $article)
	{
		$template = [
			'id'	=> (int) $article['id'],
			'title' => $article['title'],
			'content' => $article['content'],
			'comments_enabled' => (bool) $article['allow_comments'],
			'links' => [
				'rel' => 'self',
				'uri' => '/articles/'.$article['id']
			]
		];
		return $template;
	}

	public function includeGroup(Article $article)
	{
		$group = $article->group;
		return $this->item($group, new GroupTransformer);
	}
	public function includeComments(Article $article)
	{
		$comments = $article->comments;
		return $this->collection($comments, new CommentTransformer);
	}
	public function includePost(Article $article)
	{
		$post = $article->post;
		return $this->item($post, new PostTransformer);
	}
}