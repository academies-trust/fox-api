<?php 
namespace App\API\Transformers;

use App\Insurance;
use League\Fractal\TransformerAbstract;

class InsuranceTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'scheme',
	];

	public function transform(Insurance $insurance)
	{
		$template = [
			'id'	=> (int) $insurance['id'],
			'company' => $insurance['company'],
			'start' => $insurance['valid_at'],
			'expiry' => $insurance['expires_at'],
			'policy' => $insurance['policy'],
			'contact' => $insurance['contact'],
			'links' => [
				'rel' => 'self',
				'uri' => '/insurances/'.$insurance['id']
			]
		];
		return $template;
	}

	public function includeScheme(Insurance $insurance)
	{
		$scheme = $insurance->scheme;
		return $this->item($scheme, new SchemeTransformer);
	}
}