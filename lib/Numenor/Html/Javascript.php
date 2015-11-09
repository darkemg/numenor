<?php
namespace Numenor\Html;

class Javascript extends Asset {
	
	public function __construct($url, $prioridade, Remoto $remoto, $compactavel, $concatenavel) {
		parent::__construct($url, $prioridade, $remoto = null, $compactavel = true, $concatenavel = true);
	}
}