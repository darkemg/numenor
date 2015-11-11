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
	
	protected function gerarListaConcatCompact() {
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
	
	protected function gerarListaCompact() {
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
	
	protected function minificar() {
		//
		$listaConcatCompact = $this->gerarListaConcatCompact();
		$nomeConcatCompact = $this->gerarNome($listaConcatCompact);
		$minificadorConcatCompact = clone $this->minificadorJs;
		if (!file_exists($this->diretorioOutput . \DIRECTORY_SEPARATOR . $nomeConcatCompact . '.js')) {
			foreach ($listaConcatCompact as $js) {
				$minificadorConcatCompact->add($js);
			}
			$minificadorConcatCompact->execute($this->diretorioOutput . \DIRECTORY_SEPARATOR . $nomeConcatCompact);
		}
		unset($minificadorConcatCompact);
		// 
		$listaConcat = $this->gerarListaConcat();
		$nomeConcat = $this->gerarNome($listaConcat);
		if (!file_exists($this->diretorioOutput . \DIRECTORY_SEPARATOR . $nomeConcat . '.js')) {
			foreach ($listaConcat as $js) {
				file_put_contents($this->diretorioOutput . \DIRECTORY_SEPARATOR . $nomeConcat . '.js', file_get_contents($js) . "\n", FILE_APPEND);
			}
		}
		// 
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