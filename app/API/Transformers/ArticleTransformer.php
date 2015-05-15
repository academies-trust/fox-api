<?php 
namespace App\API\Transformers;

use App\Article;
use League\Fractal\TransformerAbstract;

class ArticleTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'activeContent',
	];

	protected $availableIncludes = [
		'group',
		'comments',
		'content',
	];

	public function transform(Article $article)
	{
		$template = [
			'id'	=> (int) $article['id'],
			'content' => (int) $article['content_id'],
			'comments_enabled' => (bool) $article['allow_comments'],
			'help'	=> (bool) $article['help'],
			'published' => $article['published_at'],
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
	public function includeActiveContent(Article $article)
	{
		$content = $article->activeContent;
		return $this->item($content, new ArticleContentTransformer);
	}
	public function includeContent(Article $article)
	{
		$content = $article->content;
		return $this->item($content, new ArticleContentTransformer);
	}
	public function includeComments(Article $article)
	{
		$comments = $article->comments;
		return $this->collection($comments, new CommentTransformer);
	}
}