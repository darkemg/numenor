<?php
namespace Numenor\Html;

class Css extends Asset {
	
	public function __construct($url, $prioridade, Remoto $remoto = null, $compactavel = true, $concatenavel = true) {
		parent::__construct($url, $prioridade, $remoto, $compactavel, $concatenavel);
	}
}