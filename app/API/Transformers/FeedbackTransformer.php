<?php 
namespace App\API\Transformers;

use App\Feedback;
use League\Fractal\TransformerAbstract;

class FeedbackTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'student',
		'comments',
		'markingScheme',
	];

	public function transform(Feedback $feedback)
	{
		$template = [
			'id'	=> (int) $feedback['id'],
			'content' => $feedback['content'],
			'grade' => (int) $feedback['grade'],
			'comments_enabled' => (bool) $feedback('allow_comments'),
			'links' => [
				'rel' => 'self',
				'uri' => '/feedbacks/'.$feedback['id']
			]
		];
		return $template;
	}

	public function includeMarkingScheme(Feedback $feedback)
	{
		$markingScheme = $feedback->markingScheme;
		return $this->item($markingScheme, new MarkingSchemeTransformer);
	}

	public function includeStudent(Feedback $feedback)
	{
		$student = $feedback->student;
		return $this->item($student, new UserTransformer);
	}

	public function includeComments(Feedback $feedback)
	{
		$comments = $feedback->comments;
		return $this->collection($comments, new CommentTransformer);
	}
}