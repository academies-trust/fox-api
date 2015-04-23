<?php 
namespace App\API\Transformers;

use App\Class;
use League\Fractal\TransformerAbstract;

class ClassTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'lessons',
		'group',
	];

	public function transform(Class $class)
	{
		$template = [
			'id'	=> (int) $class['id'],
			'links' => [
				'rel' => 'self',
				'uri' => '/classs/'.$class['id']
			]
		];
		return $template;
	}

	public function includeLessons(Class $class)
	{
		$lessons = $class->lessons;
		return $this->collection($lessons, new LessonTransformer);
	}
	public function includeGroup(Class $class)
	{
		$group = $class->group;
		return $this->item($group, new GroupTransformer);
	}
}