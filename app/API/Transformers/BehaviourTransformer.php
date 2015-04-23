<?php 
namespace App\API\Transformers;

use App\Behaviour;
use League\Fractal\TransformerAbstract;

class BehaviourTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'post'
	];

	protected $availableIncludes = [
		'user',
		'student',
		'behaviourModel'
	];

	public function transform(Behaviour $behaviour)
	{
		$template = [
			'id'	=> (int) $behaviour['id'],
			'positive' => (bool) $behaviour['positive'],
			'value' => (int) $behaviour['value'],
			'content' => $behaviour['content'],
			'links' => [
				'rel' => 'self',
				'uri' => '/behaviours/'.$behaviour['id']
			]
		];
		return $template;
	}

	public function includeUser(Behaviour $behaviour)
	{
		$user = $behaviour->user;
		return $this->item($user, new UserTransformer);
	}
	public function includeStudent(Behaviour $behaviour)
	{
		$student = $behaviour->student;
		return $this->item($student, new UserTransformer);
	}
	public function includeBehaviourModel(Behaviour $behaviour)
	{
		$behaviourModel = $behaviour->behaviourModel;
		return $this->item($behaviourModel, new BehaviourModelTransformer);
	}
	public function includePost(Behaviour $behaviour)
	{
		$post = $behaviour->post;
		return $this->item($post, new PostTransformer);
	}
}