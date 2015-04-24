<?php 
namespace App\API\Transformers;

use App\Device;
use League\Fractal\TransformerAbstract;

class DeviceTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'user',
		'claims',
		'scheme'
	];

	public function transform(Device $device)
	{
		$template = [
			'id'	=> (int) $device['id'],
			'case_issued' => (bool) $device['case'],
			'enrol_count' => (int) $device['enrol_count'],
			'serial' => $device['serial'],
			'deleted' => $device['deleted_at'],
			'created' => $device['created_at'],
			'updated' => $device['updated_at'],
			'links' => [
				'rel' => 'self',
				'uri' => '/devices/'.$device['id']
			]
		];
		return $template;
	}

	public function includeUser(Device $device)
	{
		$user = $device->user;
		return $this->item($user, new UserTransformer);
	}
	public function includeDeviceModel(Device $device)
	{
		$deviceModel = $device->deviceModel;
		return $this->item($deviceModel, new DeviceModelTransformer);
	}
	public function includePost(Device $device)
	{
		$post = $device->post;
		return $this->item($post, new PostTransformer);
	}
}