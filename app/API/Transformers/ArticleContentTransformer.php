<?php 
namespace App\API\Transformers;

use App\ArticleContent;
use League\Fractal\TransformerAbstract;

class ArticleContentTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'article',
		'user',
		'approvedBy'
	];

	public function transform(ArticleContent $articleContent)
	{
		$template = [
			'id'	=> (int) $articleContent['id'],
			'title'	=> $articleContent['title'],
			'content'=> $articleContent['content'],
			'approved'	=> $articleContent['approved_at'],
			'created'	=> $articleContent['created_at'],
			'updated'	=> $articleContent['updated_at'],
			'deleted'	=> $articleContent['deleted_at'],
			'links' => [
				'rel' => 'self',
				'uri' => '/articleContents/'.$articleContent['id']
			]
		];
		return $template;
	}

	public function includeArticle(ArticleContent $articleContent)
	{
		$article = $articleContent->article;
		return $this->item($article, new ArticleTransformer);
	}
	public function includeUser(ArticleContent $articleContent)
	{
		$user = $articleContent->user;
		return $this->item($user, new UserTransformer);
	}
	public function includeApprovedBy(ArticleContent $articleContent)
	{
		$approvedBy = $articleContent->approvedBy;
		return $this->item($approvedBy, new UserTransformer);
	}
}