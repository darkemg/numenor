<?php
namespace Numenor\Html;
class ControleJavascript extends Controle {
	
	/**
	 * 
	 * @var \MatthiasMullie\Minify\JS
	 */
	protected $minificadorJs;
	/**
	 * 
	 * @var \Numenor\Html\Javascript[]
	 */
	protected $listaJs;
	
	protected function gerarListaConcatCompat() {
		$lista = array();
		foreach ($this->listaJs as $js) {
			if ($js->isCompactavel() && $js->isConcatenavel()) {
				$lista[] = (string) $js;
			}
		}
		return $lista;
	}
	
	protected function gerarListaConcat() {
		$lista = array();
		foreach ($this->listaJs as $js) {
			if ($js->isConcatenavel() && !$js->isCompactavel()) {
				$lista[] = (string) $js;
			}
		}
		return $lista;
	}
	
	protected function gerarListaCompat() {
		$lista = array();
		foreach ($this->listaJs as $js) {
			if ($js->isCompactavel() && !$js->isConcatenavel()) {
				$lista[] = (string) $js;
			}
		}
		return $lista;
	}
	
	protected function gerarListaNormal() {
		$lista = array();
		foreach ($this->listaJs as $js) {
			if (!$js->isCompactavel() && !$js->isConcatenavel()) {
				$lista[] = (string) $js;
			}
		}
		return $lista;
	}
	
	public function adicionarJs(Javascript $js) {
		$this->listaJs[] = $js;
	}
	
	public function setMinificadorJs(Minify\JS $minificador) {
		$this->minificadorJs = $minificador;
	}
	
	public function gerarCodigoInclusao() {
		
	}
}