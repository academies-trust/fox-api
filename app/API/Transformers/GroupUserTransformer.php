<?php 
namespace App\API\Transformers;

use App\GroupUser;
use League\Fractal\TransformerAbstract;

class GroupUserTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'group',
		'permission',
		'user',
	];

	public function transform(GroupUser $groupUser)
	{
		$template = [
			'links' => [
				'rel' => 'self',
				'uri' => '/groupUsers/'.$groupUser['id']
			]
		];
		return $template;
	}

	public function includeGroup(GroupUser $groupUser)
	{
		$group = $groupUser->group;
		return $this->item($group, new GroupTransformer);
	}
	public function includePermission(GroupUser $groupUser)
	{
		$permission = $groupUser->permission;
		return $this->item($permission, new PermissionTransformer);
	}
	public function includeUser(GroupUser $groupUser)
	{
		$user = $groupUser->user;
		return $this->item($user, new UserTransformer);
	}
}