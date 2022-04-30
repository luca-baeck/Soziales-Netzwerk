<?php

abstract class IDFactory{
	public abstract function create(): string;

	public function addIDs(DOMElement $elem){
		$this->addID($elem);
		$nodes = $elem->getElementsByTagName('*');
		foreach ($nodes as $node) { $this->addID($node); }
	}

	private function addID(DOMNode $node){
		if ($node->hasAttribute('id')){
			if (empty($node->getAttribute('id'))){
				$node->setAttribute('id', $this->create());
			}
		}else{
			$node->setAttribute('id', $this->create());
		}
	}
}

?>