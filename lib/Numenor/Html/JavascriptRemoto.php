<?php
namespace Numenor\Html;
class JavascriptRemoto extends Javascript {
	
	protected $fallback;
	
	public function __construct($url, $fallback) {
		parent::__construct($url, false, false);
		$this->fallback = $fallback;
	}
	
	public function gerarSnippetInclusao() {
		$script = '<script src="'. $this->url .'"></script>' . "\n";
		$script .= $this->fallback;
	}
}