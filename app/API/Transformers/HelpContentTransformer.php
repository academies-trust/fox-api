<?php 
namespace App\API\Transformers;

use App\HelpContent;
use League\Fractal\TransformerAbstract;

class HelpContentTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'help',
		'user',
		'approvedBy'
	];

	public function transform(HelpContent $helpContent)
	{
		$template = [
			'id'	=> (int) $helpContent['id'],
			'title'	=> $helpContent['title'],
			'content'=> (int) $helpContent['content'],
			'active'=> (bool) $helpContent['active'],
			'approved'	=> $helpContent['approved_at'],
			'created'	=> $helpContent['created_at'],
			'updated'	=> $helpContent['updated_at'],
			'deleted'	=> $helpContent['deleted_at'],
			'links' => [
				'rel' => 'self',
				'uri' => '/helpContents/'.$helpContent['id']
			]
		];
		return $template;
	}

	public function includeHelp(HelpContent $helpContent)
	{
		$help = $helpContent->help;
		return $this->item($help, new HelpTransformer);
	}
	public function includeUser(HelpContent $helpContent)
	{
		$user = $helpContent->user;
		return $this->item($user, new UserTransformer);
	}
	public function includeApprovedBy(HelpContent $helpContent)
	{
		$approvedBy = $helpContent->approvedBy;
		return $this->item($approvedBy, new UserTransformer);
	}
}