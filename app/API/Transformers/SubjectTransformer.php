<?php 
namespace App\API\Transformers;

use App\Subject;
use League\Fractal\TransformerAbstract;

class SubjectTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'site',
		'classes',
	];

	public function transform(Subject $subject)
	{
		$template = [
			'id'	=> (int) $subject['id'],
			'name' => $subject['read_at'],
			'links' => [
				'rel' => 'self',
				'uri' => '/subjects/'.$subject['id']
			]
		];
		return $template;
	}

	public function includeSite(Subject $subject)
	{
		$site = $subject->site;
		return $this->item($site, new SiteTransformer);
	}
	public function includeClasses(Subject $subject)
	{
		$classes = $subject->classes;
		return $this->collection($classes, new ClassTransformer);
	}
}