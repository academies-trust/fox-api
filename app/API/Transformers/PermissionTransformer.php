<?php 
namespace App\API\Transformers;

use App\Permission;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'users',
	];

	public function transform(Permission $permission)
	{
		$template = [
			'name' => $permission['name'],
			'read' => (bool) $permission['read'],
			'contribute' => (bool) $permission['contribute'],
			'write' => (bool) $permission['write'],
			'admin' => (bool) $permission['admin'],
			'own' => (bool) $permission['own'],
			'links' => [
				'rel' => 'self',
				'uri' => '/permissions/'.$permission['id']
			]
		];
		return $template;
	}

	public function includeUsers(Permission $permission)
	{
		$users = $permission->users;
		return $this->collection($users, new UsersTransformer);
	}
}