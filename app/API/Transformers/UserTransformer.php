<?php 
namespace App\API\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'sites',
		'groupUsers',
		'siteUsers',
		'groups',
		'posts',
		'feedbacks',
		'submissions',
		'markingSchemes',
		'helpContents',
		'approvedHelpContents',
		'behaviours',
		'behavioursOut',
		'claims',
		'devices',
	];

	public function transform(User $user)
	{
		$template = [
			'id'	=> $user['id'],
			'name' 	=> $user['name'],
			'email' 	=> $user['email'],
			'username' 	=> $user['username'],
			'links' => [
				'rel' => 'self',
				'uri' => '/users/'.$user['id']
			]
		];
		return $template;
	}

	public function includeSites(User $user)
	{
		$sites = $user->sites;
		return $this->collection($sites, new SiteTransformer);
	}

	public function includeGroupUsers(User $user)
	{
		$groupUsers = $user->groupUsers;
		return $this->collection($groupUsers, new GroupUserTransformer);
	}

	public function includeSiteUsers(User $user)
	{
		$siteUsers = $user->siteUsers;
		return $this->collection($siteUsers, new SiteUserTransformer);
	}
	public function includeGroups(User $user)
	{
		$groups = $user->groups;
		return $this->collection($groups, new GroupTransformer);
	}
	public function includePosts(User $user)
	{
		$posts = $user->posts;
		return $this->collection($posts, new PostTransformer);
	}
	public function includeFeedbacks(User $user)
	{
		$feedbacks = $user->feedbacks;
		return $this->collection($feedbacks, new FeedbackTransformer);
	}
	public function includeSubmissions(User $user)
	{
		$submissions = $user->submissions;
		return $this->collection($submissions, new SubmissionTransformer);
	}
	public function includeMarkingSchemes(User $user)
	{
		$markingSchemes = $user->markingSchemes;
		return $this->collection($markingSchemes, new MarkingSchemeTransformer);
	}
	public function includeHelpContents(User $user)
	{
		$helpContents = $user->helpContents;
		return $this->collection($helpContents, new HelpContentTransformer);
	}
	public function includeApprovedHelpContents(User $user)
	{
		$approvedHelpContents = $user->approvedHelpContents;
		return $this->collection($approvedHelpContents, new ApprovedHelpContentTransformer);
	}
	public function includeBehaviours(User $user)
	{
		$behaviours = $user->behaviours;
		return $this->collection($behaviours, new BehaviourTransformer);
	}
	public function includeBehavioursOut(User $user)
	{
		$behavioursOut = $user->behavioursOut;
		return $this->collection($behavioursOut, new BehavioursOutTransformer);
	}
	public function includeClaims(User $user)
	{
		$claims = $user->claims;
		return $this->collection($claims, new ClaimTransformer);
	}
	public function includeDevices(User $user)
	{
		$devices = $user->devices;
		return $this->collection($devices, new DeviceTransformer);
	}
}