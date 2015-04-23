<?php 
namespace App\API\Transformers;

use App\Grading;
use League\Fractal\TransformerAbstract;

class GradingTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'post'
	];

	protected $availableIncludes = [
		'markingScheme',
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
}