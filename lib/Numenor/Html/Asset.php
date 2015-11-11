<?php
namespace Numenor\Html;
abstract class Asset {
	
	protected $url;
	protected $compactavel;
	protected $concatenavel;
	
	public function __construct($url, $compactavel = true, $concatenavel = true) {
		$this->url = $url;
		$this->compactavel = $compactavel;
		$this->concatenavel = $concatenavel;
	}
	
	public function __toString() {
		return $this->url;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function isCompactavel()  {
		return $this->compactavel;
	}
	
	public function isConcatenavel() {
		return $this->concatenavel;
	}
	
}