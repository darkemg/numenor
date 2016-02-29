<?php
/**
 * Classe abstrata representando um assets para inclusão em uma página HTML.
 *
 * @abstract
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Html
 */
namespace Numenor\Html;
abstract class Asset {
	
	/**
	 * Caminho físico do arquivo asset.
	 * 
	 * @access protected
	 * @var string
	 */
	protected $caminhoArquivo;
	/**
	 * Indica se o asset é minificável.
	 * 
	 * @access protected
	 * @var boolean
	 */
	protected $compactavel;
	/**
	 * Indica se o asset é concatenável.
	 * 
	 * @access protected
	 * @var boolean
	 */
	protected $concatenavel;
	
	/**
	 * Método construtor da classe.
	 * 
	 * @access public
	 * @param string $caminhoArquivo URL do arquivo do asset.
	 * @param boolean $compactavel Indica se o asset pode ser compactado através de um minificador. 
	 * @param boolean $concatenavel Infica se o asset pode ser concatenado com outros assets que possuem a mesma
	 * propriedade.
	 */
	public function __construct($caminhoArquivo, $compactavel = true, $concatenavel = true) {
		$this->caminhoArquivo = $caminhoArquivo;
		$this->compactavel = $compactavel;
		$this->concatenavel = $concatenavel;
	}
	
	/**
	 * Método mágico de conversão do objeto para string.
	 * 
	 * @access public
	 * @return string A representação do objeto como string.
	 */
	public function __toString() {
		return $this->caminhoArquivo;
	}
	
	/**
	 * Método getter do caminho físico do arquivo do asset.
	 * 
	 * @access public
	 * @return string Caminho físico do arquivo do asset.
	 */
	public function getCaminhoArquivo() {
		return $this->caminhoArquivo;
	}
	
	/**
	 * Indica se o asset é minificável.
	 * 
	 * @access public
	 * @return boolean O asset é minificável?
	 */
	public function isCompactavel()  {
		return $this->compactavel;
	}
	
	/**
	 * Indica se o asset é concatenável.
	 * 
	 * @access public
	 * @return boolean O asset é concatenável?
	 */
	public function isConcatenavel() {
		return $this->concatenavel;
	}	
}