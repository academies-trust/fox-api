<?php 
namespace App\API\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'clients'
	];

	public function transform(User $user)
	{
		$template = [
			'id'	=> $user['id'],
			'name' 	=> $user['name'],
			'email' 	=> $user['email'],
			'links' => [
				'rel' => 'self',
				'uri' => '/users/'.$user['id']
			]
		];
		return $template;
	}

	public function includeClients(User $user)
	{
		$clients = $user->clients;
		return $this->collection($clients, new ClientTransformer);
	}
}