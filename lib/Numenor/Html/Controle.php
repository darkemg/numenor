<?php
namespace Numenor\Html;
use Numenor\Php\ArrayWrapper;

class Controle {
	
	protected $arrayWrapper;
	protected $css;
	protected $js;
	
	public function __construct(ArrayWrapper $arrayWrapper, array $css = array(), array $js = array()) {
		$this->arrayWrapper = $arrayWrapper;
		$this->css = $css;
		$this->js = $js;
	}
	
	public function getCss() {
		return $this->css;
	}
	
	public function getJs() {
		return $this->js;
	}
	
	public function adicionarCss(Asset $asset) {
		
	}
	
	public function adicionarJs(Javascript $js) {
		if (sizeof($this->js) == 0) {
			$this->js[] = $js;
		} else {
			foreach ($this->js as $javascript) {
				
			}
		}
	}
}