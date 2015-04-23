<?php 
namespace App\API\Transformers;

use App\Submission;
use League\Fractal\TransformerAbstract;

class SubmissionTransformer extends TransformerAbstract {

	protected $defaultIncludes = [
		'post'
	];

	protected $availableIncludes = [
		'group',
		'comments',
		'submissions',
		'markingScheme',
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

	public function includeGroup(Submission $submission)
	{
		$group = $submission->group;
		return $this->item($group, new GroupTransformer);
	}
	public function includeComments(Submission $submission)
	{
		$comments = $submission->comments;
		return $this->collection($comments, new CommentTransformer);
	}
	public function includeSubmissions(Submission $submission)
	{
		$submissions = $submission->submissions;
		return $this->collection($submissions, new SubmissionTransformer);
	}	
	public function includePost(Submission $submission)
	{
		$post = $submission->post;
		return $this->item($post, new PostTransformer);
	}
	public function includeFeedback(Submission $submission)
	{
		$feedback = $submission->feedback;
		return $this->item($feedback, new FeedbackTransformer);
	}
}