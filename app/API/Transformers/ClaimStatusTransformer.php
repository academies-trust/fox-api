<?php 
namespace App\API\Transformers;

use App\ClaimStatus;
use League\Fractal\TransformerAbstract;

class ClaimStatusTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'claims',
		'updates',
	];

	public function transform(ClaimStatus $claimStatus)
	{
		$template = [
			'id'	=> (int) $claimStatus['id'],
			'name' => $claimStatus['name'],
			'links' => [
				'rel' => 'self',
				'uri' => '/claimStatuss/'.$claimStatus['id']
			]
		];
		return $template;
	}

	public function includeClaims(ClaimStatus $claimStatus)
	{
		$claims = $claimStatus->claims;
		return $this->collection($claims, new ClaimTransformer);
	}
	public function includeUpdates(ClaimStatus $claimStatus)
	{
		$updates = $claimStatus->updates;
		return $this->collection($updates, new ClaimUpdateTransformer);
	}
}