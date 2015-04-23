<?php 
namespace App\API\Transformers;

use App\Lesson;
use League\Fractal\TransformerAbstract;

class LessonTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'event',
	];
	protected $availableIncludes = [
		'class',
	];

	public function transform(Lesson $class)
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

	public function includeClass(Lesson $lesson)
	{
		$class = $lesson->class;
		return $this->item($class, new ClassTransformer);
	}
	public function includeEvent(Lesson $lesson)
	{
		$event = $lesson->event;
		return $this->item($event, new EventTransformer);
	}
}