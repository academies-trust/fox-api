<?php 
namespace App\API\Transformers;

use App\Help;
use League\Fractal\TransformerAbstract;

class HelpTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'post'
	];

	protected $availableIncludes = [
		'helpContent',
	];

	public function transform(Help $help)
	{
		$template = [
			'id'	=> (int) $help['id'],
			'start' => $help['starts_at'],
			'end' => $help['ends_at'],
			'comments_enabled' => (bool) $help['allow_comments'],
			'links' => [
				'rel' => 'self',
				'uri' => '/helps/'.$help['id']
			]
		];
		return $template;
	}

	public function includeHelpContent(Help $help)
	{
		$helpContent = $help->content;
		return $this->item($helpContent, new HelpContentTransformer);
	}
	public function includePost(Help $help)
	{
		$post = $help->post;
		return $this->item($post, new PostTransformer);
	}
}