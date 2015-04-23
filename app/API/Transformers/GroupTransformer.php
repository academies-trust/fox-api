<?php 
namespace App\API\Transformers;

use App\Group;
use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'sites',
		'class',
		'resources',
		'modules',
		'events',
		'notices',
		'articles',
		'tasks',
		'users',
		'site',
		'groupUsers',
	];

	public function transform(Group $group)
	{
		$template = [
			'id'	=> (int) $group['id'],
			'name' 	=> $group['name'],
			'open' 	=> (bool) $group['open'],
			'service_provider' => (bool) $group['service_provider'],
			'links' => [
				'rel' => 'self',
				'uri' => '/groups/'.$group['id']
			]
		];
		return $template;
	}

	public function includeSites(Group $group)
	{
		$sites = $group->sites;
		return $this->collection($sites, new SiteTransformer);
	}
	public function includeClass(Group $group)
	{
		$class = $group->class;
		return $this->item($class, new ClassTransformer);
	}
	public function includeResources(Group $group)
	{
		$resources = $group->resources;
		return $this->collection($resource, new ResourceTransformer);
	}
	public function includeModules(Group $group)
	{
		$modules = $group->modules;
		return $this->collection($modules, new ModuleTransformer);
	}
	public function includeEvents(Group $group)
	{
		$events = $group->events;
		return $this->collection($events, new EventTransformer);
	}
	public function includeNotices(Group $group)
	{
		$notices = $group->notices;
		return $this->collection($notices, new NoticeTransformer);
	}
	public function includeArticles(Group $group)
	{
		$articles = $group->articles;
		return $this->collection($articles, new ArticleTransformer);
	}
	public function includeTasks(Group $group)
	{
		$tasks = $group->tasks;
		return $this->collection($tasks, new TaskTransformer);
	}
	public function includeUsers(Group $group)
	{
		$users = $group->users;
		return $this->collection($users, new UserTransformer);
	}
	public function includeGroupUsers(Group $group)
	{
		$groupUsers = $group->groupUsers;
		return $this->collection($groupUsers, new GroupUserTransformer);
	}
	public function includeSite(Group $group)
	{
		$site = $group->site;
		return $this->item($site, new SiteTransformer);
	}
}