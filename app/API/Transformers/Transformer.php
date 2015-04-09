<?php 
namespace app\API\Transformers;
use League\Fractal;

abstract class Transformer extends Fractal\TransformerAbstract {

	public function transformCollection($items)
	{
		return array_map([$this, 'transform'], $items);
	}

	public abstract function transform($item);
}