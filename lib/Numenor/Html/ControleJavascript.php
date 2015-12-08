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
	/**
	 * 
	 * @var array
	 */
	protected $listaArquivosIncluir;
	
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
		$this->listaArquivosIncluir = array();
		//
		$listaConcatCompact = $this->gerarListaConcatCompact();
		$nomeConcatCompact = $this->gerarNome($listaConcatCompact);
		$minificadorConcatCompact = clone $this->minificadorJs;
		$outputConcatCompact = $this->diretorioOutput . \DIRECTORY_SEPARATOR . $nomeConcatCompact . '.js';
		if (!file_exists($outputConcatCompact)) {
			foreach ($listaConcatCompact as $js) {
				$minificadorConcatCompact->add($js);
			}
			$minificadorConcatCompact->gzip($outputConcatCompact);
		}
		$this->listaArquivosIncluir[] = $outputConcatCompact;
		unset($minificadorConcatCompact);
		// 
		$listaConcat = $this->gerarListaConcat();
		$nomeConcat = $this->gerarNome($listaConcat);
		$outputConcat = $this->diretorioOutput . \DIRECTORY_SEPARATOR . $nomeConcat . '.js';
		if (!file_exists($outputConcat)) {
			foreach ($listaConcat as $js) {
				file_put_contents(
						$outputConcat, 
						gzencode(file_get_contents($js . "\n"), 9, FORCE_GZIP), 
						FILE_APPEND);
			}
		}
		$this->listaArquivosIncluir[] = $outputConcat;
		// 
		$listaCompact = $this->gerarListaCompact();
		foreach ($listaCompact as $js) {
			$minificadorCompact = clone $this->minificadorJs;
			$nomeCompact = $this->gerarNome(array($js));
			$outputCompact = $this->diretorioOutput . \DIRECTORY_SEPARATOR . $nomeCompact . '.js';
			if (!file_exists($outputCompact)) {
				$minificadorCompact->add($js);
				$minificadorCompact->gzip($outputCompact);
			}
			$this->listaArquivosIncluir[] = $outputCompact;
			unset($minificadorCompact);
		}
		$listaNormal = $this->gerarListaNormal();
		foreach ($listaNormal as $js) {
			if ($js instanceof JavascriptRemoto) {
				
			} else {
				$this->listaArquivosIncluir[] = (string) $js;
			}
		}
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