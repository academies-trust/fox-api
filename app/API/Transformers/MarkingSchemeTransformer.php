<?php 
namespace App\API\Transformers;

use App\MarkingScheme;
use League\Fractal\TransformerAbstract;

class MarkingSchemeTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'site',
		'gradings',
		'feedbacks',
		'user',
	];

	public function transform(MarkingScheme $markingScheme)
	{
		$template = [
			'id'	=> $markingScheme['id'],
			'name' 	=> $markingScheme['name'],
			'links' => [
				'rel' => 'self',
				'uri' => '/markingSchemes/'.$markingScheme['id']
			]
		];
		return $template;
	}

	public function includeSite(MarkingScheme $markingScheme)
	{
		$site = $markingScheme->site;
		return $this->item($site, new SiteTransformer);
	}

	public function includeUser(MarkingScheme $markingScheme)
	{
		$user = $markingScheme->user;
		return $this->item($user, new UserTransformer);
	}

	public function includeGradings(MarkingScheme $markingScheme)
	{
		$gradings = $markingScheme->gradings;
		return $this->collection($gradings, new GradingTransformer);
	}

	public function includeFeedbacks(MarkingScheme $markingScheme)
	{
		$feedbacks = $markingScheme->feedbacks;
		return $this->collection($feedbacks, new FeedbackTransformer);
	}
	
}