<?php 
namespace App\API\Transformers;

use App\Grading;
use League\Fractal\TransformerAbstract;

class GradingTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'markingScheme',
		'posts'
	];

	public function transform(Grading $grading)
	{
		$template = [
			'id'	=> (int) $grading['id'],
			'name' => $grading['name'],
			'grade' => (int) $grading['grade'],
			'links' => [
				'rel' => 'self',
				'uri' => '/gradings/'.$grading['id']
			]
		];
		return $template;
	}

	public function includeMarkingScheme(Grading $grading)
	{
		$markingScheme = $grading->markingScheme;
		return $this->item($markingScheme, new MarkingSchemeTransformer);
	}

	public function includePosts(Grading $grading)
	{
		$posts = $grading->readable;
		return $this->collection($posts, new PostTransformer);
	}
}