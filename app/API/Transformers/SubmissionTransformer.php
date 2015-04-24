<?php 
namespace App\API\Transformers;

use App\Submission;
use League\Fractal\TransformerAbstract;

class SubmissionTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'post'
	];

	protected $availableIncludes = [
		'task',
		'owner',
		'feedback'
	];

	public function transform(Submission $submission)
	{
		$template = [
			'id'	=> (int) $submission['id'],
			'read' => $submission['read_at'],
			'submitted' => $submission['submitted_at'],
			'marked' => $submission['marked_at'],
			'comments_enabled' => (bool) $submission['allow_comments'],
			'links' => [
				'rel' => 'self',
				'uri' => '/submissions/'.$submission['id']
			]
		];
		return $template;
	}

	public function includeTask(Submission $submission)
	{
		$task = $submission->task;
		return $this->item($task, new TaskTransformer);
	}
	public function includeOwner(Submission $submission)
	{
		$owner = $submission->owner;
		return $this->item($owner, new UserTransformer);
	}
	public function includeFeedback(Submission $submission)
	{
		$feedback = $submission->feedback;
		return $this->item($feedback, new FeedbackTransformer);
	}
}