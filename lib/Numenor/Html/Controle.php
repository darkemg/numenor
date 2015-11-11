<?php
namespace Numenor\Html;
use MatthiasMullie\Minify;
use Numenor\Configuracao\Biblioteca;
use Numenor\Php\ArrayWrapper;
use Numenor\Php\StringWrapper;

abstract class Controle {
	
	/**
	 * 
	 * @var \Numenor\Php\ArrayWrapper;
	 */
	protected $arrayWrapper;
	/**
	 * 
	 * @var \Numenor\Php\StringWrapper;
	 */
	protected $stringWrapper;
	protected $diretorioOutput;
	
	protected function gerarNome(array $listaAssets) {
		return hash('sha1', $this->stringWrapper->unir('', $listaAssets));
	}
	
	public function __construct(ArrayWrapper $arrayWrapper, StringWrapper $stringWrapper, $diretorioOutput) {
		$this->arrayWrapper = $arrayWrapper;
		$this->stringWrapper = $stringWrapper;
		$this->diretorioOutput = $diretorioOutput;
	}
	
	abstract public function gerarCodigoInclusao();
}