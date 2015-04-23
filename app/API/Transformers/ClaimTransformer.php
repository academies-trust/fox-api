<?php 
namespace App\API\Transformers;

use App\Claim;
use League\Fractal\TransformerAbstract;

class ClaimTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'device',
		'status',
		'updates',
	];

	public function transform(Claim $claim)
	{
		$template = [
			'id'	=> (int) $claim['id'],
			'occurence' => $claim['incident_at'],
			'type' => $claim['type'],
			'details' => $claim['details'],
			'created' => $claim['created_at'],
			'updated' => $claim['updated_at'],
			'deleted' => $claim['deleted_at'],
			'links' => [
				'rel' => 'self',
				'uri' => '/claims/'.$claim['id']
			]
		];
		return $template;
	}

	public function includeDevice(Claim $claim)
	{
		$device = $claim->device;
		return $this->item($device, new DeviceTransformer);
	}
	public function includeStatus(Claim $claim)
	{
		$status = $claim->status;
		return $this->item($status, new StatusTransformer);
	}
	public function includeUpdates(Claim $claim)
	{
		$updates = $claim->updates;
		return $this->collection($updates, new UpdateTransformer);
	}
}