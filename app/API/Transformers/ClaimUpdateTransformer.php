<?php 
namespace App\API\Transformers;

use App\ClaimUpdate;
use League\Fractal\TransformerAbstract;

class ClaimUpdateTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'status',
		'claim',
	];

	public function transform(ClaimUpdate $claimUpdate)
	{
		$template = [
			'id'	=> (int) $claimUpdate['id'],
			'content' => $claimUpdate['content'],
			'created' => $claimUpdate['created_at'],
			'updated' => $claimUpdate['updated_at'],
			'deleted' => $claimUpdate['deleted_at'],
			'links' => [
				'rel' => 'self',
				'uri' => '/claimUpdates/'.$claimUpdate['id']
			]
		];
		return $template;
	}

	public function includeClaim(ClaimUpdate $claimUpdate)
	{
		$claim = $claimUpdate->claim;
		return $this->item($claim, new ClaimTransformer);
	}
	public function includeStatus(ClaimUpdate $claimUpdate)
	{
		$status = $claimUpdate->status;
		return $this->item($status, new ClaimStatusTransformer);
	}
}