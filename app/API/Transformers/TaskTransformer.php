<?php 
namespace App\API\Transformers;

use App\Task;
use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'group',
		'comments',
		'submissions',
		'markingScheme',
		'posts',
	];

	public function transform(Task $task)
	{
		$template = [
			'id'	=> (int) $task['id'],
			'due' => $task['due_at'],
			'comments_enabled' => (bool) $task['allow_comments'],
			'links' => [
				'rel' => 'self',
				'uri' => '/tasks/'.$task['id']
			]
		];
		return $template;
	}

	public function includeGroup(Task $task)
	{
		$group = $task->group;
		return $this->item($group, new GroupTransformer);
	}
	public function includeComments(Task $task)
	{
		$comments = $task->comments;
		return $this->collection($comments, new CommentTransformer);
	}
	public function includeSubmissions(Task $task)
	{
		$submissions = $task->submissions;
		return $this->collection($submissions, new SubmissionTransformer);
	}	
	public function includePosts(Task $task)
	{
		$posts = $task->readable;
		return $this->collection($posts, new PostTransformer);
	}
}