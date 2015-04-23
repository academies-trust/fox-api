<?php 
namespace App\API\Transformers;

use App\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'users',
	];

	public function transform(Role $role)
	{
		$template = [
			'id'	=> $role['id'],
			'name' 	=> $role['name'],
			'email' 	=> $role['email'],
			'links' => [
				'rel' => 'self',
				'uri' => '/roles/'.$role['id']
			]
		];
		return $template;
	}

	public function includeUsers(Role $role)
	{
		$users = $role->users;
		return $this->collection($users, new UserTransformer);
	}
}