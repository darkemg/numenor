<?php
namespace Numenor\Html;
class Remoto {
	
	private $urlFallback;
	private $testeFallback;
	
	public function __construct($urlFallback, $testeFallback = null) {
		$this->urlFallback = $urlFallback;
		$this->testeFallback = $testeFallback;
	}
	
	public function getUrlFallback() {
		return $this->urlFallback;
	}
	
	public function getTesteFallback() {
		return $this->testeFallback;
	}
}